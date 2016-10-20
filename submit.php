<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

//defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
if (!defined('XOOPS_ROOT_PATH')) {
    include __DIR__ . '/../../mainfile.php';
}
include_once __DIR__ . '/header.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
include_once XOOPS_ROOT_PATH . '/class/uploader.php';
include_once XOOPS_ROOT_PATH . '/header.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
if (file_exists(XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/admin.php')) {
    include_once XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/admin.php';
} else {
    include_once XOOPS_ROOT_PATH . '/modules/news/language/english/admin.php';
}
$myts      = MyTextSanitizer::getInstance();
$module_id = $xoopsModule->getVar('mid');
$storyid   = 0;

if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}

$gperm_handler = xoops_getHandler('groupperm');

if (isset($_POST['topic_id'])) {
    $perm_itemid = (int)$_POST['topic_id'];
} else {
    $perm_itemid = 0;
}
//If no access
if (!$gperm_handler->checkRight('news_submit', $perm_itemid, $groups, $module_id)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}
$op = 'form';

//If approve privileges
$approveprivilege = 0;
if (is_object($xoopsUser) && $gperm_handler->checkRight('news_approve', $perm_itemid, $groups, $module_id)) {
    $approveprivilege = 1;
}

if (isset($_POST['preview'])) {
    $op = 'preview';
} elseif (isset($_POST['post'])) {
    $op = 'post';
} elseif (isset($_GET['op']) && isset($_GET['storyid'])) {
    // Verify that the user can edit or delete an article
    if ($_GET['op'] === 'edit' || $_GET['op'] === 'delete') {
        if ($xoopsModuleConfig['authoredit'] == 1) {
            $tmpstory = new NewsStory((int)$_GET['storyid']);
            if (is_object($xoopsUser) && $xoopsUser->getVar('uid') != $tmpstory->uid() && !news_is_admin_group()) {
                redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
            }
        } else { // Users can't edit their articles
            if (!news_is_admin_group()) {
                redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
            }
        }
    }

    if ($approveprivilege && $_GET['op'] === 'edit') {
        $op      = 'edit';
        $storyid = (int)$_GET['storyid'];
    } elseif ($approveprivilege && $_GET['op'] === 'delete') {
        $op      = 'delete';
        $storyid = (int)$_GET['storyid'];
    } else {
        if (news_getmoduleoption('authoredit') && is_object($xoopsUser) && isset($_GET['storyid'])
            && ($_GET['op'] === 'edit'
                || $_POST['op'] === 'preview'
                || $_POST['op'] === 'post')
        ) {
            $storyid = 0;
            $storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : (int)$_POST['storyid'];
            if (!empty($storyid)) {
                $tmpstory = new NewsStory($storyid);
                if ($tmpstory->uid() == $xoopsUser->getVar('uid')) {
                    $op = isset($_GET['op']) ? $_GET['op'] : $_POST['post'];
                    unset($tmpstory);
                    $approveprivilege = 1;
                } else {
                    unset($tmpstory);
                    if (!news_is_admin_group()) {
                        redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
                    } else {
                        $approveprivilege = 1;
                    }
                }
            }
        } else {
            if (!news_is_admin_group()) {
                unset($tmpstory);
                redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
            } else {
                $approveprivilege = 1;
            }
        }
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
        if (!$gperm_handler->checkRight('news_view', $story->topicid(), $groups, $module_id)) {
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
        if ($story->published() != 0) {
            $published = $story->published();
        }
        if ($story->expired() != 0) {
            $expired = $story->expired();
        } else {
            $expired = 0;
        }
        $type         = $story->type();
        $topicdisplay = $story->topicdisplay();
        $topicalign   = $story->topicalign(false);
        if (!news_is_admin_group()) {
            include_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        } else {
            include_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.original.php';
        }
        echo '</td></tr></table>';
        break;

    case 'preview':
        $topic_id = (int)$_POST['topic_id'];
        $xt       = new NewsTopic($topic_id);
        if (isset($_GET['storyid'])) {
            $storyid = (int)$_GET['storyid'];
        } else {
            if (isset($_POST['storyid'])) {
                $storyid = (int)$_POST['storyid'];
            } else {
                $storyid = 0;
            }
        }

        if (!empty($storyid)) {
            $story     = new NewsStory($storyid);
            $published = $story->published();
            $expired   = $story->expired();
        } else {
            $story     = new NewsStory();
            $published = isset($_POST['publish_date']) ? $_POST['publish_date'] : 0;
            if (!empty($published) && isset($_POST['autodate']) && (int)($_POST['autodate'] == 1)) {
                $published = strtotime($published['date']) + $published['time'];
            } else {
                $published = 0;
            }
            $expired = isset($_POST['expiry_date']) ? $_POST['expiry_date'] : 0;
            if (!empty($expired) && isset($_POST['autoexpdate']) && (int)($_POST['autoexpdate'] == 1)) {
                $expired = strtotime($expired['date']) + $expired['time'];
            } else {
                $expired = 0;
            }
        }
        $topicid = $topic_id;
        if (isset($_POST['topicdisplay'])) {
            $topicdisplay = (int)$_POST['topicdisplay'];
        } else {
            $topicdisplay = 1;
        }

        $approve    = isset($_POST['approve']) ? (int)$_POST['approve'] : 0;
        $topicalign = 'R';
        if (isset($_POST['topicalign'])) {
            $topicalign = $_POST['topicalign'];
        }
        $story->setTitle($_POST['title']);
        $story->setSubtitle($_POST['subtitle']);
        $story->setHometext($_POST['hometext']);
        if ($approveprivilege) {
            $story->setTopicdisplay($topicdisplay);
            $story->setTopicalign($topicalign);
            $story->setBodytext($_POST['bodytext']);
            if (news_getmoduleoption('metadata')) {
                $story->setKeywords($_POST['keywords']);
                $story->setDescription($_POST['description']);
                $story->setIhome((int)$_POST['ihome']);
            }
        } else {
            $noname = isset($_POST['noname']) ? (int)$_POST['noname'] : 0;
        }

        if ($approveprivilege || (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid()))) {
            if (isset($_POST['author'])) {
                $story->setUid((int)$_POST['author']);
            }
        }

        $notifypub = isset($_POST['notifypub']) ? (int)$_POST['notifypub'] : 0;
        $nosmiley  = isset($_POST['nosmiley']) ? (int)$_POST['nosmiley'] : 0;
        if (isset($nosmiley) && ($nosmiley == 0 || $nosmiley == 1)) {
            $story->setNosmiley($nosmiley);
        } else {
            $nosmiley = 0;
        }
        if ($approveprivilege) {
            $nohtml = isset($_POST['nohtml']) ? (int)$_POST['nohtml'] : 0;
            $story->setNohtml($nohtml);
            if (!isset($_POST['approve'])) {
                $approve = 0;
            }
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
        $p_hometext  = (($xt->topic_imgurl() !== '') && $topicdisplay) ? '<img src="assets/images/topics/'
                                                                         . $xt->topic_imgurl()
                                                                         . '" '
                                                                         . $topicalign2
                                                                         . ' alt="" />'
                                                                         . $p_hometext : $p_hometext;
        themecenterposts($p_title, $p_hometext);

        //Display post edit form
        $returnside = (int)$_POST['returnside'];
        include_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        break;

    case 'post':
        $nohtml_db = isset($_POST['nohtml']) ? $_POST['nohtml'] : 1;
        if (is_object($xoopsUser)) {
            $uid = $xoopsUser->getVar('uid');
            if ($approveprivilege) {
                $nohtml_db = empty($_POST['nohtml']) ? 0 : 1;
            }
            if (isset($_POST['author']) && ($approveprivilege || $xoopsUser->isAdmin($xoopsModule->mid()))) {
                $uid = (int)$_POST['author'];
            }
        } else {
            $uid = 0;
        }

        if (isset($_GET['storyid'])) {
            $storyid = (int)$_GET['storyid'];
        } else {
            if (isset($_POST['storyid'])) {
                $storyid = (int)$_POST['storyid'];
            } else {
                $storyid = 0;
            }
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
        $story->setTopicId((int)$_POST['topic_id']);
        $story->setHostname(xoops_getenv('REMOTE_ADDR'));
        $story->setNohtml($nohtml_db);
        $nosmiley = isset($_POST['nosmiley']) ? (int)$_POST['nosmiley'] : 0;
        $story->setNosmiley($nosmiley);
        $notifypub = isset($_POST['notifypub']) ? (int)$_POST['notifypub'] : 0;
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
            $expiry_date = $expiry_date - ($offset * 3600);
            $story->setExpired($expiry_date);
        } else {
            $story->setExpired(0);
        }

        if ($approveprivilege) {
            if (news_getmoduleoption('metadata')) {
                $story->setDescription($_POST['description']);
                $story->setKeywords($_POST['keywords']);
            }
            $story->setTopicdisplay($_POST['topicdisplay']); // Display Topic Image ? (Yes or No)
            $story->setTopicalign($_POST['topicalign']); // Topic Align, 'Right' or 'Left'
            $story->setIhome($_POST['ihome']); // Publish in home ? (Yes or No)
            if (isset($_POST['bodytext'])) {
                $story->setBodytext($_POST['bodytext']);
            } else {
                $story->setBodytext(' ');
            }
            $approve = isset($_POST['approve']) ? (int)$_POST['approve'] : 0;

            if (!$story->published() && $approve) {
                $story->setPublished(time());
            }
            if (!$story->expired()) {
                $story->setExpired(0);
            }

            if (!$approve) {
                $story->setPublished(0);
            }
        } elseif ($xoopsModuleConfig['autoapprove'] == 1 && !$approveprivilege) {
            if (empty($storyid)) {
                $approve = 1;
            } else {
                $approve = isset($_POST['approve']) ? (int)$_POST['approve'] : 0;
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
            news_updateCache();
        }

        // Increment author's posts count (only if it's a new article)
        // First case, it's not an anonyous, the story is approved and it's a new story
        if ($uid && $approve && empty($storyid)) {
            $tmpuser        = new xoopsUser($uid);
            $member_handler = xoops_getHandler('member');
            $member_handler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
        }

        // Second case, it's not an anonymous, the story is NOT approved and it's NOT a new story (typical when someone is approving a submited story)
        if (is_object($xoopsUser) && $approve && !empty($storyid)) {
            $storytemp = new NewsStory($storyid);
            if (!$storytemp->published() && $storytemp->uid() > 0) { // the article has been submited but not approved
                $tmpuser        = new xoopsUser($storytemp->uid());
                $member_handler = xoops_getHandler('member');
                $member_handler->updateUserByField($tmpuser, 'posts', $tmpuser->getVar('posts') + 1);
            }
            unset($storytemp);
        }

        $allowupload = false;
        switch ($xoopsModuleConfig['uploadgroups']) {
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

        if ($allowupload && isset($_POST['deleteimage']) && (int)$_POST['deleteimage'] == 1) {
            $currentPicture = $story->picture();
            if (xoops_trim($currentPicture) !== '') {
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
            if (isset($_POST['xoops_upload_file'])) {
                $fldname = $_FILES[$_POST['xoops_upload_file'][1]];
                $fldname = $fldname['name'];
                if (xoops_trim($fldname !== '')) {
                    $sfiles         = new sFiles();
                    $destname       = $sfiles->createUploadName(XOOPS_ROOT_PATH . '/uploads/news/image', $fldname);
                    $permittedtypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png');
                    $uploader       = new XoopsMediaUploader(XOOPS_ROOT_PATH . '/uploads/news/image', $permittedtypes,
                                                             $xoopsModuleConfig['maxuploadsize']);
                    $uploader->setTargetFileName($destname);
                    if ($uploader->fetchMedia($_POST['xoops_upload_file'][1])) {
                        if ($uploader->upload()) {
                            $fullPictureName = XOOPS_ROOT_PATH . '/uploads/news/image/' . basename($destname);
                            $newName         = XOOPS_ROOT_PATH . '/uploads/news/image/redim_' . basename($destname);
                            news_resizePicture($fullPictureName, $newName, $xoopsModuleConfig['maxwidth'], $xoopsModuleConfig['maxheight']);
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
            if (xoops_isActiveModule('tag') && news_getmoduleoption('tags')) {
                $tag_handler = xoops_getModuleHandler('tag', 'tag');
                $tag_handler->updateByItem($_POST['item_tag'], $story->storyid(), $xoopsModule->getVar('dirname'), 0);
            }

            if (!$editmode) {
                //  Notification
                // TODO: modifier afin qu'en cas de pr�publication, la notification ne se fasse pas
                $notification_handler = xoops_getHandler('notification');
                $tags                 = array();
                $tags['STORY_NAME']   = $story->title();
                $tags['STORY_URL']    = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/article.php?storyid=' . $story->storyid();
                // If notify checkbox is set, add subscription for approve
                if ($notifypub && $approve) {
                    include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                    $notification_handler->subscribe('story', $story->storyid(), 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE,
                                                     $xoopsModule->getVar('mid'), $story->uid());
                }

                if ($approve == 1) {
                    $notification_handler->triggerEvent('global', 0, 'new_story', $tags);
                    $notification_handler->triggerEvent('story', $story->storyid(), 'approve', $tags);
                    // Added by Lankford on 2007/3/23
                    $notification_handler->triggerEvent('category', $story->topicid(), 'new_story', $tags);
                } else {
                    $tags['WAITINGSTORIES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=newarticle';
                    $notification_handler->triggerEvent('global', 0, 'story_submit', $tags);
                }
            }

            if ($allowupload) {
                // Manage upload(s)
                if (isset($_POST['delupload']) && count($_POST['delupload']) > 0) {
                    foreach ($_POST['delupload'] as $onefile) {
                        $sfiles = new sFiles($onefile);
                        $sfiles->delete();
                    }
                }

                if (isset($_POST['xoops_upload_file'])) {
                    $fldname = $_FILES[$_POST['xoops_upload_file'][0]];
                    $fldname = $fldname['name'];
                    if (xoops_trim($fldname !== '')) {
                        $sfiles   = new sFiles();
                        $destname = $sfiles->createUploadName(XOOPS_UPLOAD_PATH, $fldname);
                        /**
                         * You can attach files to your news
                         */
                        $permittedtypes = explode("\n", str_replace("\r", '', news_getmoduleoption('mimetypes')));
                        array_walk($permittedtypes, 'trim');
                        $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, $permittedtypes, $xoopsModuleConfig['maxuploadsize']);
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
        $returnside = isset($_POST['returnside']) ? (int)$_POST['returnside'] : 0;
        if (!$returnside) {
            redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_THANKS);
        } else {
            redirect_header(XOOPS_URL . '/modules/news/admin/index.php?op=newarticle', 2, _NW_THANKS);
        }
        break;

    case 'form':
        $xt        = new NewsTopic();
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
        if ($xoopsModuleConfig['autoapprove'] == 1) {
            $approve = 1;
        }
        include_once XOOPS_ROOT_PATH . '/modules/news/include/storyform.inc.php';
        break;
}
include_once XOOPS_ROOT_PATH . '/footer.php';
