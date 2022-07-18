<?php declare(strict_types=1);
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
 * @author         XOOPS Development Team
 */

use Xmf\Request;
use XoopsModules\News;
use XoopsModules\News\Files;
use XoopsModules\News\NewsTopic;
use XoopsModules\Tag\FormTag;

$moduleDirName = \basename(\dirname(__DIR__));
xoops_load('utility', $moduleDirName);
xoops_loadLanguage('calendar');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/modules/news/config.php';

if (!isset($subtitle)) {
    $subtitle = '';
}

$sform = new \XoopsThemeForm(_NW_SUBMITNEWS, 'storyform', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/submit.php');
$sform->setExtra('enctype="multipart/form-data"');
$sform->addElement(new \XoopsFormText(_NW_TITLE, 'title', 50, 255, $title), true);
$sform->addElement(new \XoopsFormText(_NW_SUBTITLE, 'subtitle', 50, 255, $subtitle), false);

// Topic's selection box
if (!isset($xt)) {
    $xt = new NewsTopic();
}
if (0 == $xt->getAllTopicsCount()) {
    redirect_header('index.php', 4, _NW_POST_SORRY);
}

require_once XOOPS_ROOT_PATH . '/class/tree.php';
$allTopics  = $xt->getAllTopics($helper->getConfig('restrictindex'), 'news_submit');
$topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');

if (News\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
    $topic_select = $topic_tree->makeSelectElement('topic_id', 'topic_title', '--', $topicid, false, 0, '', _NW_TOPIC);
    $sform->addElement($topic_select);
} else {
    $topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $topicid, false);
    $sform->addElement(new \XoopsFormLabel(_NW_TOPIC, $topic_select));
}

//If admin - show admin form
//TODO: Change to "If submit privilege"

if ($approveprivilege) {
    //Show topic image?
    $sform->addElement(new \XoopsFormRadioYN(_AM_TOPICDISPLAY, 'topicdisplay', $topicdisplay));
    //Select image position
    $posselect = new \XoopsFormSelect(_AM_TOPICALIGN, 'topicalign', $topicalign);
    $posselect->addOption('R', _AM_RIGHT);
    $posselect->addOption('L', _AM_LEFT);
    $sform->addElement($posselect);
    //Publish in home?
    //TODO: Check that pubinhome is 0 = no and 1 = yes (currently vice versa)
    $sform->addElement(new \XoopsFormRadioYN(_AM_PUBINHOME, 'ihome', $ihome, _NO, _YES));
}

// News author

if ($approveprivilege && is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    if (!isset($newsauthor)) {
        $newsauthor = $xoopsUser->getVar('uid');
    }
    /** @var \XoopsMemberHandler $memberHandler */
    $memberHandler = xoops_getHandler('member');
    $usercount     = $memberHandler->getUserCount();
    if ($usercount < $cfg['config_max_users_list']) {
        $sform->addElement(new \XoopsFormSelectUser(_NW_AUTHOR, 'author', true, $newsauthor), false);
    } else {
        $sform->addElement(new \XoopsFormText(_NW_AUTHOR_ID, 'author', 10, 10, $newsauthor), false);
    }
}

$editor = News\Utility::getWysiwygForm(_NW_THESCOOP, 'hometext', $hometext, 15, 60, 'hometext_hidden');
$sform->addElement($editor, true);

//Extra info
//If admin -> if submit privilege

if ($approveprivilege) {
    $editor2 = News\Utility::getWysiwygForm(_AM_EXTEXT, 'bodytext', $bodytext, 15, 60, 'bodytext_hidden');
    $sform->addElement($editor2, false);

    if (News\Utility::getModuleOption('tags') && \class_exists(\XoopsModules\Tag\FormTag::class) && xoops_isActiveModule('tag')) {
        $itemIdForTag = $storyid ?? 0;
        $sform->addElement(new \XoopsModules\Tag\FormTag('item_tag', 60, 255, $itemIdForTag, 0));
    }

    if (News\Utility::getModuleOption('metadata')) {
        $sform->addElement(new xoopsFormText(_NW_META_DESCRIPTION, 'description', 50, 255, $description), false);
        $sform->addElement(new xoopsFormText(_NW_META_KEYWORDS, 'keywords', 50, 255, $keywords), false);
    }
} elseif (News\Utility::getModuleOption('tags') && \class_exists(\XoopsModules\Tag\FormTag::class) && xoops_isActiveModule('tag')) {
    $itemIdForTag = $storyid ?? 0;
    $sform->addElement(new \XoopsModules\Tag\FormTag('item_tag', 60, 255, $itemIdForTag, 0));
}

// Manage upload(s)
$allowupload = false;
switch ($helper->getConfig('uploadgroups')) {
    case 1: //Submitters and Approvers
        $allowupload = true;
        break;
    case 2: //Approvers only
        $allowupload = $approveprivilege;
        break;
    case 3: //Upload Disabled
        $allowupload = false;
        break;
}

if ($allowupload) {
    if ('edit' === $op) {
        $sfiles   = new Files();
        $filesarr = [];
        $filesarr = $sfiles->getAllbyStory($storyid);
        if (count($filesarr) > 0) {
            $upl_tray     = new \XoopsFormElementTray(_AM_UPLOAD_ATTACHFILE, '<br>');
            $upl_checkbox = new \XoopsFormCheckBox('', 'delupload[]');

            foreach ($filesarr as $onefile) {
                $link = sprintf("<a href='%s/%s' target='_blank'>%s</a>\n", XOOPS_UPLOAD_URL, $onefile->getDownloadname('S'), $onefile->getFileRealName('S'));
                $upl_checkbox->addOption($onefile->getFileid(), $link);
            }
            $upl_tray->addElement($upl_checkbox, false);
            $dellabel = new \XoopsFormLabel(_AM_DELETE_SELFILES, '');
            $upl_tray->addElement($dellabel, false);
            $sform->addElement($upl_tray);
        }
    }
    $sform->addElement(new \XoopsFormFile(_AM_SELFILE, 'attachedfile', $helper->getConfig('maxuploadsize')), false);
    if ('edit' === $op) {
        if (isset($picture) && '' !== xoops_trim($picture)) {
            $pictureTray = new \XoopsFormElementTray(_NW_CURENT_PICTURE, '<br>');
            $pictureTray->addElement(new \XoopsFormLabel('', "<img src='" . XOOPS_URL . '/uploads/news/image/' . $picture . "'>"));
            $deletePicureCheckbox = new \XoopsFormCheckBox('', 'deleteimage', 0);
            $deletePicureCheckbox->addOption(1, _DELETE);
            $pictureTray->addElement($deletePicureCheckbox);
            $sform->addElement($pictureTray);
        }
    }
    if (!isset($pictureinfo)) {
        $pictureinfo = '';
    }
    $sform->addElement(new \XoopsFormFile(_NW_SELECT_IMAGE, 'attachedimage', $helper->getConfig('maxuploadsize')), false);
    $sform->addElement(new \XoopsFormText(_NW_SELECT_IMAGE_DESC, 'pictureinfo', 50, 255, $pictureinfo), false);
}

$option_tray = new \XoopsFormElementTray(_OPTIONS, '<br>');
//Set date of publish/expiration
if ($approveprivilege) {
    $approve_checkbox = new \XoopsFormCheckBox('', 'approve', $approve);
    $approve_checkbox->addOption(1, _AM_APPROVE);
    $option_tray->addElement($approve_checkbox);

    $check              = $published > 0 ? 1 : 0;
    $published_checkbox = new \XoopsFormCheckBox('', 'autodate', $check);
    $published_checkbox->addOption(1, _AM_SETDATETIME);
    $option_tray->addElement($published_checkbox);

    $option_tray->addElement(new \XoopsFormDateTime(_AM_SETDATETIME, 'publish_date', 15, $published));

    $check            = $expired > 0 ? 1 : 0;
    $expired_checkbox = new \XoopsFormCheckBox('', 'autoexpdate', $check);
    $expired_checkbox->addOption(1, _AM_SETEXPDATETIME);
    $option_tray->addElement($expired_checkbox);

    $option_tray->addElement(new \XoopsFormDateTime(_AM_SETEXPDATETIME, 'expiry_date', 15, $expired));
}

if (is_object($xoopsUser)) {
    $notify_checkbox = new \XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, _NW_NOTIFYPUBLISH);
    $option_tray->addElement($notify_checkbox);
    if ($xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $nohtml_checkbox = new \XoopsFormCheckBox('', 'nohtml', $nohtml);
        $nohtml_checkbox->addOption(1, _DISABLEHTML);
        $option_tray->addElement($nohtml_checkbox);
    }
}
$smiley_checkbox = new \XoopsFormCheckBox('', 'nosmiley', $nosmiley);
$smiley_checkbox->addOption(1, _DISABLESMILEY);
$option_tray->addElement($smiley_checkbox);

$sform->addElement($option_tray);

//Submit buttons
$buttonTray  = new \XoopsFormElementTray('', '');
$preview_btn = new \XoopsFormButton('', 'preview', _PREVIEW, 'submit');
$preview_btn->setExtra('accesskey="p"');
$buttonTray->addElement($preview_btn);
$submit_btn = new \XoopsFormButton('', 'post', _NW_POST, 'submit');
$submit_btn->setExtra('accesskey="s"');
$buttonTray->addElement($submit_btn);
$sform->addElement($buttonTray);

//Hidden variables
if (isset($storyid)) {
    $sform->addElement(new \XoopsFormHidden('storyid', $storyid));
}

if (!isset($returnside)) {
    $returnside = Request::getInt('returnside', 0, 'POST');
    if (empty($returnside)) {
        $returnside = Request::getInt('returnside', 0, 'GET');
    }
}

if (!isset($returnside)) {
    $returnside = 0;
}
$sform->addElement(new \XoopsFormHidden('returnside', $returnside), false);

if (!isset($type)) {
    if ($approveprivilege) {
        $type = 'admin';
    } else {
        $type = 'user';
    }
}
$type_hidden = new \XoopsFormHidden('type', $type);
$sform->addElement($type_hidden);

echo '<h1>' . _NW_SUBMITNEWS . '</h1>';
if ('' !== xoops_trim(News\Utility::getModuleOption('submitintromsg'))) {
    echo "<div class='infotext'><br><br>" . nl2br(News\Utility::getModuleOption('submitintromsg')) . '<br><br></div>';
}

$sform->display();
