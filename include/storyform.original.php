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
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

use XoopsModules\News;

xoops_loadLanguage('calendar');

require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
;
require_once XOOPS_ROOT_PATH . '/modules/news/config.php';

if (!isset($subtitle)) {
    $subtitle = '';
}
if (!isset($pictureinfo)) {
    $pictureinfo = '';
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
$allTopics  = $xt->getAllTopics($xoopsModuleConfig['restrictindex'], 'news_submit');
$topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');

$moduleDirName = basename(dirname(__DIR__));
xoops_load('utility', $moduleDirName);

if (News\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
    //    $topic_select = $topic_tree->makeSelBox('topic_id', 'topic_title', '-- ', $topicid, false);
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

    if (xoops_isActiveModule('tag') && News\Utility::getModuleOption('tags')) {
        $itemIdForTag = isset($storyid) ? $storyid : 0;
        require_once XOOPS_ROOT_PATH . '/modules/tag/include/formtag.php';
        $sform->addElement(new TagFormTag('item_tag', 60, 255, $itemIdForTag, 0));
    }

    if (News\Utility::getModuleOption('metadata')) {
        $sform->addElement(new xoopsFormText(_NW_META_DESCRIPTION, 'description', 50, 255, $description), false);
        $sform->addElement(new xoopsFormText(_NW_META_KEYWORDS, 'keywords', 50, 255, $keywords), false);
    }
}

// Manage upload(s)
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

if ($allowupload) {
    if ('edit' === $op) {
        $sfiles   = new sFiles();
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
    $sform->addElement(new \XoopsFormFile(_AM_SELFILE, 'attachedfile', $xoopsModuleConfig['maxuploadsize']), false);
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
    $sform->addElement(new \XoopsFormFile(_NW_SELECT_IMAGE, 'attachedimage', $xoopsModuleConfig['maxuploadsize']), false);
    $sform->addElement(new \XoopsFormText(_NW_SELECT_IMAGE_DESC, 'pictureinfo', 50, 255, $pictureinfo), false);
}

$option_tray = new \XoopsFormElementTray(_OPTIONS, '<br>');
//Set date of publish/expiration
if ($approveprivilege) {
    if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
        $approve = 1;
    }
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

//TODO: Approve checkbox + "Move to top" if editing + Edit indicator

//Submit buttons
$button_tray = new \XoopsFormElementTray('', '');
$preview_btn = new \XoopsFormButton('', 'preview', _PREVIEW, 'submit');
$preview_btn->setExtra('accesskey="p"');
$button_tray->addElement($preview_btn);
$submit_btn = new \XoopsFormButton('', 'post', _NW_POST, 'submit');
$submit_btn->setExtra('accesskey="s"');
$button_tray->addElement($submit_btn);
$sform->addElement($button_tray);

//Hidden variables
if (isset($storyid)) {
    $sform->addElement(new \XoopsFormHidden('storyid', $storyid));
}

if (!isset($returnside)) {
    $returnside = isset($_POST['returnside']) ? (int)$_POST['returnside'] : 0;
    if (empty($returnside)) {
        $returnside = isset($_GET['returnside']) ? (int)$_GET['returnside'] : 0;
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
$sform->display();
