<?php
//
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                                    //
// Copyright (c) 2000-2016 XOOPS.org                                             //
// <http://xoops.org/>                                                  //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
//                                                                          //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
//                                                                          //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
//                                                                          //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
include_once __DIR__ . '/../../../include/cp_header.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/xoopstopic.php';
include_once XOOPS_ROOT_PATH . '/class/xoopslists.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
include_once XOOPS_ROOT_PATH . '/modules/news/admin/functions.php';
include_once __DIR__ . '/admin_header.php';
xoops_cp_header();
$permAdmin = new ModuleAdmin();
echo $permAdmin->addNavigation(basename(__FILE__));

echo '<br><br><br>';
$permtoset                = isset($_POST['permtoset']) ? (int)$_POST['permtoset'] : 1;
$selected                 = array('', '', '');
$selected[$permtoset - 1] = ' selected';
echo "<form method='post' name='fselperm' action='groupperms.php'><select name='permtoset' onChange='javascript: document.fselperm.submit()'><option value='1'"
     . $selected[0]
     . '>'
     . _AM_APPROVEFORM
     . "</option><option value='2'"
     . $selected[1]
     . '>'
     . _AM_SUBMITFORM
     . "</option><option value='3'"
     . $selected[2]
     . '>'
     . _AM_VIEWFORM
     . "</option></select> <input type='submit' name='go'></form>";
$module_id = $xoopsModule->getVar('mid');

switch ($permtoset) {
    case 1:
        $title_of_form = _AM_APPROVEFORM;
        $perm_name     = 'news_approve';
        $perm_desc     = _AM_APPROVEFORM_DESC;
        break;
    case 2:
        $title_of_form = _AM_SUBMITFORM;
        $perm_name     = 'news_submit';
        $perm_desc     = _AM_SUBMITFORM_DESC;
        break;
    case 3:
        $title_of_form = _AM_VIEWFORM;
        $perm_name     = 'news_view';
        $perm_desc     = _AM_VIEWFORM_DESC;
        break;
}

$permform  = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/groupperms.php');
$xt        = new MyXoopsTopic($xoopsDB->prefix('news_topics'));
$alltopics = $xt->getTopicsList();

if ($alltopics) {
    foreach ($alltopics as $topic_id => $topic) {
        $permform->addItem($topic_id, $topic['title'], $topic['pid']);
    }
    echo $permform->render();
    echo "<br><br><br><br>\n";
    unset($permform);
} else {
    redirect_header('index.php?op=topicsmanager', 5, _NW_NEWS_NO_TOPICS, false);
}

include_once __DIR__ . '/admin_footer.php';
