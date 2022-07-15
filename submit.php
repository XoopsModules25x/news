<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

use Xmf\Request;
use XoopsModules\News;
use XoopsModules\News\Files;
use XoopsModules\News\NewsStory;
use XoopsModules\News\NewsTopic;
use XoopsModules\Tag\Helper;

if (!defined('XOOPS_ROOT_PATH')) {
    require_once dirname(__DIR__, 2) . '/mainfile.php';
}
require_once __DIR__ . '/header.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
require_once XOOPS_ROOT_PATH . '/class/uploader.php';
require_once XOOPS_ROOT_PATH . '/header.php';

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();
$helper->loadLanguage('admin');

$myts      = \MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
$storyid   = 0;

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');

if (Request::hasVar('topic_id', 'POST')) {
    $perm_itemid = Request::getInt('topic_id', 0, 'POST');
} else {
    $perm_itemid = 0;
}
//If no access
if (!$grouppermHandler->checkRight('news_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}
$op = 'form';

//If approve privileges
$approveprivilege = 0;
if (is_object($xoopsUser) && $grouppermHandler->checkRight('news_approve', $perm_itemid, $groups, $module_id)) {
    $approveprivilege = 1;
}

if (Request::hasVar('preview', 'POST')) {
    $op = 'preview';
} elseif (Request::hasVar('post', 'POST')) {
    $op = 'post';
} elseif (Request::hasVar('op', 'GET') && Request::hasVar('storyid', 'GET')) {
    // Verify that the user can edit or delete an article
    if ('edit' === $_GET['op'] || 'delete' === $_GET['op']) {
        if (1 == $helper->getConfig('authoredit')) {
            $tmpstory = new NewsStory(Request::getInt('storyid', 0, 'GET'));
            if (is_object($xoopsUser) && $xoopsUser->getVar('uid') != $tmpstory->uid() && !News\Utility::isAdminGroup()) {
                redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
            }
        } elseif (!News\Utility::isAdminGroup()) {
            // Users can't edit their articles
            redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
        }
    }

    if ($approveprivilege && 'edit' === $_GET['op']) {
        $op      = 'edit';
        $storyid = Request::getInt('storyid', 0, 'GET');
    } elseif ($approveprivilege && 'delete' === $_GET['op']) {
        $op      = 'delete';
        $storyid = Request::getInt('storyid', 0, 'GET');
    } elseif (News\Utility::getModuleOption('authoredit') && is_object($xoopsUser) && isset($_GET['storyid'])
              && ('edit' === $_GET['op']
                  || 'preview' === $_POST['op']
                  || 'post' === $_POST['op'])) {
        $storyid = 0;
        //            $storyid = isset($_GET['storyid']) ? \Xmf\Request::getInt('storyid', 0, 'GET') : \Xmf\Request::getInt('storyid', 0, 'POST');
        $storyid = Request::getInt('storyid', 0);
        if (!empty($storyid)) {
            $tmpstory = new NewsStory($storyid);
            if ($tmpstory->uid() == $xoopsUser->getVar('uid')) {
                $op = isset($_GET['op']) ? $_GET['op'] : $_POST['post'];
                unset($tmpstory);
                $approveprivilege = 1;
            } else {
                unset($tmpstory);
                if (!News\Utility::isAdminGroup()) {
                    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
                } else {
                    $approveprivilege = 1;
                }
            }
        }
    } elseif (!News\Utility::isAdminGroup()) {
        unset($tmpstory);
        redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
    } else {
        $approveprivilege = 1;
    }
}

switch ($op) {
    case 'edit':
        if (!$approveprivilege) {
            redirect_header(XOOPS_URL . '/modules/news/index.php', 0, _NOPERM);

            break;
        }
        //if ($storyid==0 && isset($_POST['storyid'])) {
        //$storyid=(int)($_POST['storyid']);
        //}
        $story = new NewsStory($storyid);
        if (!$grouppermHandler->checkRight('news_view', $story->topicid(), $groups, $module_id)) {
            redirect_header(XOOPS_URL . '/modules/news/index.php', 0, _NOPERM);
        }
        echo "<table width='100%' border='0' cellspacing='1' class='outer'><tr><td class=\"odd\">";
        echo '<h4>' . _AM_EDITARTICLE . '</h4>';
        $title       = $story->title('Edit');
        $subtitle    = $story->subtitle('Edit');
        $hometext    = $story->hometext('Edit');
        $bodytext    = $story->bodytext('Edit');
        $nohtml      = $story->nohtml();
        $nosmiley    = $story->nosmiley();
        $description = $story->description();
        $keywords    = $story->keywords();
        $ihome       = $story->ihome();
        $newsauthor  = $story->uid();
        $topicid     = $story->topicid();
        $notifypub   = $story->notifypub();
        $picture     = $story->picture();
        $pictureinfo = $story->pictureinfo;
        $approve     = 0;
        $published   = $story->published();
        if (isset($published) && $published > 0) {
            $approve = 1;
        }
        if (0 != $story->published()) {
            $published = $story->published();
        }
        if (0 != $story->expired()) {
            $expired = $story->expired();
        } else {
            $expired = 0;
        }
        $type         = $story->type();
        $topicdisplay = $story->topicdisplay();
        $topicalign   = $story->topicalign(false);
        if (!News\Utility::isAdminGroup()) {
            require_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        } else {
            require_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.original.php';
        }
        echo '</td></tr></table>';
        break;
    case 'preview':
        $topic_id = Request::getInt('topic_id', 0, 'POST');
        $xt       = new  NewsTopic($topic_id);
        if (Request::hasVar('storyid', 'GET')) {
            $storyid = Request::getInt('storyid', 0, 'GET');
        } elseif (Request::hasVar('storyid', 'POST')) {
            $storyid = Request::getInt('storyid', 0, 'POST');
        } else {
            $storyid = 0;
        }

        if (!empty($storyid)) {
            $story     = new NewsStory($storyid);
            $published = $story->published();
            $expired   = $story->expired();
        } else {
            $story     = new NewsStory();
            $published = Request::getInt('publish_date', 0, 'POST');
            if (!empty($published) && isset($_POST['autodate']) && (int)(1 == $_POST['autodate'])) {
                $published = strtotime($published['date']) + $published['time'];
            } else {
                $published = 0;
            }
            $expired = Request::getInt('expiry_date', 0, 'POST');
            if (!empty($expired) && isset($_POST['autoexpdate']) && (int)(1 == $_POST['autoexpdate'])) {
                $expired = strtotime($expired['date']) + $expired['time'];
            } else {
                $expired = 0;
            }
        }
        $topicid = $topic_id;
        if (Request::hasVar('topicdisplay', 'POST')) {
            $topicdisplay = Request::getInt('topicdisplay', 0, 'POST');
        } else {
            $topicdisplay = 1;
        }

        $approve    = Request::getInt('approve', 0, 'POST');
        $topicalign = 'R';
        if (Request::hasVar('topicalign', 'POST')) {
            $topicalign = $_POST['topicalign'];
        }
        $story->setTitle($_POST['title']);
        $story->setSubtitle($_POST['subtitle']);
        $story->setHometext($_POST['hometext']);
        if ($approveprivilege) {
            $story->setTopicdisplay($topicdisplay);
            $story->setTopicalign($topicalign);
            $story->setBodytext($_POST['bodytext']);
            if (News\Utility::getModuleOption('metadata')) {
                $story->setKeywords($_POST['keywords']);
                $story->setDescription($_POST['description']);
                $story->setIhome(Request::getInt('ihome', 0, 'POST'));
            }
        } else {
            $noname = Request::getInt('noname', 0, 'POST');
        }

        if ($approveprivilege || (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid()))) {
            if (Request::hasVar('author', 'POST')) {
                $story->setUid(Request::getInt('author', 0, 'POST'));
            }
        }

        $notifypub = Request::getInt('notifypub', 0, 'POST');
        $nosmiley  = Request::getInt('nosmiley', 0, 'POST');
        if (isset($nosmiley) && (0 == $nosmiley || 1 == $nosmiley)) {
            $story->setNosmiley($nosmiley);
        } else {
            $nosmiley = 0;
        }
        if ($approveprivilege) {
            $nohtml = Request::getInt('nohtml', 0, 'POST');
            $story->setNohtml($nohtml);
        } else {
            $story->setNohtml = 1;
        }

        $title    = $story->title('InForm');
        $subtitle = $story->subtitle('InForm');
        $hometext = $story->hometext('InForm');
        if ($approveprivilege) {
            $bodytext    = $story->bodytext('InForm');
            $ihome       = $story->ihome();
            $description = $story->description('E');
            $keywords    = $story->keywords('E');
        }
        $pictureinfo = $story->pictureinfo('InForm');

        //Display post preview
        $newsauthor = $story->uid();
        $p_title    = $story->title('Preview');
        $p_hometext = $story->hometext('Preview');
        if ($approveprivilege) {
            $p_bodytext = $story->bodytext('Preview');
            $p_hometext .= '<br><br>' . $p_bodytext;
        }
        $topicalign2 = isset($story->topicalign) ? 'align="' . $story->topicalign() . '"' : '';
        $p_hometext  = (('' !== $xt->topic_imgurl()) && $topicdisplay) ? '<img src="assets/images/topics/' . $xt->topic_imgurl() . '" ' . $topicalign2 . ' alt="">' . $p_hometext : $p_hometext;
        themecenterposts($p_title, $p_hometext);

        //Display post edit form
        $returnside = Request::getInt('returnside', 0, 'POST');
        require_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        break;
    case 'post':
        $nohtml_db = Request::getInt('nohtml', 1, 'POST');
        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->getVar('uid');
            if ($approveprivilege) {
                $nohtml_db = empty($_POST['nohtml']) ? 0 : 1;
            }
            if (Request::hasVar('author', 'POST') && ($approveprivilege || $xoopsUser->isAdmin($xoopsModule->mid()))) {
                $uid = Request::getInt('author', 0, 'POST');
            }
        } else {
            $uid = 0;
        }

        if (Request::hasVar('storyid', 'GET')) {
            $storyid = Request::getInt('storyid', 0, 'GET');
        } elseif (Request::hasVar('storyid', 'POST')) {
            $storyid = Request::getInt('storyid', 0, 'POST');
        } else {
            $storyid = 0;
        }

        if (empty($storyid)) {
            $story    = new NewsStory();
            $editmode = false;
        } else {
            $story    = new NewsStory($storyid);
            $editmode = true;
        }
        $story->setUid($uid);
        $story->setTitle($_POST['title']);
        $story->setSubtitle($_POST['subtitle']);
        $story->setHometext($_POST['hometext']);
        $story->setTopicId(Request::getInt('topic_id', 0, 'POST'));
        $story->setHostname(xoops_getenv('REMOTE_ADDR'));
        $story->setNohtml($nohtml_db);
        $nosmiley = Request::getInt('nosmiley', 0, 'POST');
        $story->setNosmiley($nosmiley);
        $notifypub = Request::getInt('notifypub', 0, 'POST');
        $story->setNotifyPub($notifypub);
        $story->setType($_POST['type']);

        if (!empty($_POST['autodate']) && $approveprivilege) {
            $publish_date = $_POST['publish_date'];
            $pubdate      = strtotime($publish_date['date']) + $publish_date['time'];
            //$offset = $xoopsUser -> timezone() - $xoopsConfig['server_TZ'];
            //$pubdate = $pubdate - ( $offset * 3600 );
            $story->setPublished($pubdate);
        }
        if (!empty($_POST['autoexpdate']) && $approveprivilege) {
            $expiry_date = $_POST['expiry_date'];
            $expiry_date = strtotime($expiry_date['date']) + $expiry_date['time'];
            $offset      = $xoopsUser->timezone() - $xoopsConfig['server_TZ'];
            $expiry_date -= ($offset * 3600);
            $story->setExpired($expiry_date);
        } else {
            $story->setExpired(0);
        }

        if ($approveprivilege) {
            if (News\Utility::getModuleOption('metadata')) {
                $story->setDescription($_POST['description']);
                $story->setKeywords($_POST['keywords']);
            }
            $story->setTopicdisplay($_POST['topicdisplay']); // Display Topic Image ? (Yes or No)
            $story->setTopicalign($_POST['topicalign']); // Topic Align, 'Right' or 'Left'
            $story->setIhome($_POST['ihome']); // Publish in home ? (Yes or No)
            if (Request::hasVar('bodytext', 'POST')) {
                $story->setBodytext($_POST['bodytext']);
            } else {
                $story->setBodytext(' ');
            }
            $approve = Request::getInt('approve', 0, 'POST');

            if (!$story->published() && $approve) {
                $story->setPublished(time());
            }
            if (!$story->expired()) {
                $story->setExpired(0);
            }

            if (!$approve) {
                $story->setPublished(0);
            }
        } elseif (1 == $helper->getConfig('autoapprove')) {
            if (empty($storyid)) {
                $approve = 1;
            } else {
                $approve = Request::getInt('approve', 0, 'POST');
            }
            if ($approve) {
                $story->setPublished(time());
            } else {
                $story->setPublished(0);
            }
            $story->setExpired(0);
            $story->setTopicalign('R');
        } else {
            $approve = 0;
        }
        $story->setApproved($approve);

        if ($approve) {
            News\Utility::updateCache();
        }

        // Increment author's posts count (only if it's a new article)
        // First case, it's not an anonyous, the story is approved and it's a new story
        if ($uid && $approve && empty($storyid)) {
            $tmpuser = new xoopsUser($uid);
            /** @var \XoopsMemberHandler $memberHandler */
            $memberHandler = xoops_getHandler('member');
            $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
        }

        // Second case, it's not an anonymous, the story is NOT approved and it's NOT a new story (typical when someone is approving a submited story)
        if (is_object($xoopsUser) && $approve && !empty($storyid)) {
            $storytemp = new NewsStory($storyid);
            if (!$storytemp->published() && $storytemp->uid() > 0) { // the article has been submited but not approved
                $tmpuser = new xoopsUser($storytemp->uid());
                /** @var \XoopsMemberHandler $memberHandler */
                $memberHandler = xoops_getHandler('member');
                $memberHandler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
            }
            unset($storytemp);
        }

        $allowupload = false;
        switch ($helper->getConfig('uploadgroups')) {
            case 1: //Submitters and Approvers
                $allowupload = true;
                break;
            case 2: //Approvers only
                $allowupload = $approveprivilege ? true : false;
                break;
            case 3: //Upload Disabled
                $allowupload = false;
                break;
        }

        if ($allowupload && isset($_POST['deleteimage']) && 1 == Request::getInt('deleteimage', 0, 'POST')) {
            $currentPicture = $story->picture();
            if ('' !== xoops_trim($currentPicture)) {
                $currentPicture = XOOPS_ROOT_PATH . '/uploads/news/image/' . xoops_trim($story->picture());
                if (is_file($currentPicture) && file_exists($currentPicture)) {
                    if (!unlink($currentPicture)) {
                        trigger_error('Error, impossible to delete the picture attached to this article');
                    }
                }
            }
            $story->setPicture('');
            $story->setPictureinfo('');
        }

        if ($allowupload) { // L'image
            if (Request::hasVar('xoops_upload_file', 'POST')) {
                $fldname = $_FILES[$_POST['xoops_upload_file'][1]];
                $fldname = $fldname['name'];
                if (xoops_trim('' !== $fldname)) {
                    $sfiles         = new Files();
                    $destname       = $sfiles->createUploadName(XOOPS_ROOT_PATH . '/uploads/news/image', $fldname);
                    $permittedtypes = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'];
                    $uploader       = new \XoopsMediaUploader(XOOPS_ROOT_PATH . '/uploads/news/image', $permittedtypes, $helper->getConfig('maxuploadsize'));
                    $uploader->setTargetFileName($destname);
                    if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
                        if ($uploader->upload()) {
                            $fullPictureName = XOOPS_ROOT_PATH . '/uploads/news/image/' . basename($destname);
                            $newName         = XOOPS_ROOT_PATH . '/uploads/news/image/redim_' . basename($destname);
                            News\Utility::resizePicture($fullPictureName, $newName, $helper->getConfig('maxwidth'), $helper->getConfig('maxheight'));
                            if (file_exists($newName)) {
                                @unlink($fullPictureName);
                                rename($newName, $fullPictureName);
                            }
                            $story->setPicture(basename($destname));
                        } else {
                            echo _AM_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                        }
                    } else {
                        echo $uploader->getErrors();
                    }
                }
                $story->setPictureinfo($_POST['pictureinfo']);
            }
        }
        $destname = '';

        $result = $story->store();
        if ($result) {
            if (xoops_isActiveModule('tag') && News\Utility::getModuleOption('tags')) {
                $tagHandler = Helper::getInstance()->getHandler('Tag');
                $tagHandler->updateByItem($_POST['item_tag'], $story->storyid(), $xoopsModule->getVar('dirname'), 0);
            }

            if (!$editmode) {
                //  Notification
                // TODO: modify so that in case of pre-publication, the notification is not made
                /** @var \XoopsNotificationHandler $notificationHandler */
                $notificationHandler = xoops_getHandler('notification');
                $tags                = [];
                $tags['STORY_NAME']  = $story->title();
                $tags['STORY_URL']   = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/article.php?storyid=' . $story->storyid();
                // If notify checkbox is set, add subscription for approve
                if ($notifypub && $approve) {
                    require_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notificationHandler->subscribe('story', $story->storyid(), 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE, $xoopsModule->getVar('mid'), $story->uid());
                }

                if (1 == $approve) {
                    $notificationHandler->triggerEvent('global', 0, 'new_story', $tags);
                    $notificationHandler->triggerEvent('story', $story->storyid(), 'approve', $tags);
                    // Added by Lankford on 2007/3/23
                    $notificationHandler->triggerEvent('category', $story->topicid(), 'new_story', $tags);
                } else {
                    $tags['WAITINGSTORIES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=newarticle';
                    $notificationHandler->triggerEvent('global', 0, 'story_submit', $tags);
                }
            }

            if ($allowupload) {
                // Manage upload(s)
                if (Request::hasVar('delupload', 'POST') && count($_POST['delupload']) > 0) {
                    foreach ($_POST['delupload'] as $onefile) {
                        $sfiles = new Files($onefile);
                        $sfiles->delete();
                    }
                }

                if (Request::hasVar('xoops_upload_file', 'POST')) {
                    $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
                    $fldname = $fldname['name'];
                    if (xoops_trim('' !== $fldname)) {
                        $sfiles   = new Files();
                        $destname = $sfiles->createUploadName(XOOPS_UPLOAD_PATH, $fldname);
                        /**
                         * You can attach files to your news
                         */
                        $permittedtypes = explode("\n", str_replace("\r", '', News\Utility::getModuleOption('mimetypes')));
                        array_walk($permittedtypes, '\trim');
                        $uploader = new \XoopsMediaUploader(XOOPS_UPLOAD_PATH, $permittedtypes, $helper->getConfig('maxuploadsize'));
                        $uploader->setTargetFileName($destname);
                        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                            if ($uploader->upload()) {
                                $sfiles->setFileRealName($uploader->getMediaName());
                                $sfiles->setStoryid($story->storyid());
                                $sfiles->setMimetype($sfiles->giveMimetype(XOOPS_UPLOAD_PATH . '/' . $uploader->getMediaName()));
                                $sfiles->setDownloadname($destname);
                                if (!$sfiles->store()) {
                                    echo _AM_UPLOAD_DBERROR_SAVE;
                                }
                            } else {
                                echo _AM_UPLOAD_ERROR . ' ' . $uploader->getErrors();
                            }
                        } else {
                            echo $uploader->getErrors();
                        }
                    }
                }
            }
        } else {
            echo _ERRORS;
        }
        $returnside = Request::getInt('returnside', 0, 'POST');
        if (!$returnside) {
            redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_THANKS);
        } else {
            redirect_header(XOOPS_URL . '/modules/news/admin/index.php?op=newarticle', 2, _NW_THANKS);
        }
        break;
    case 'form':
        $xt        = new  NewsTopic();
        $title     = '';
        $subtitle  = '';
        $hometext  = '';
        $noname    = 0;
        $nohtml    = 0;
        $nosmiley  = 0;
        $notifypub = 1;
        $topicid   = 0;
        if ($approveprivilege) {
            $description  = '';
            $keywords     = '';
            $topicdisplay = 0;
            $topicalign   = 'R';
            $ihome        = 0;
            $bodytext     = '';
            $approve      = 0;
            $autodate     = '';
            $expired      = 0;
            $published    = 0;
        }
        if (1 == $helper->getConfig('autoapprove')) {
            $approve = 1;
        }
        require_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        break;
}
require_once XOOPS_ROOT_PATH . '/footer.php';
