<?php
// $Id$
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
include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';

// We verify that the user can post comments **********************************
if (!isset($xoopsModuleConfig)) {
    die();
}

if ($xoopsModuleConfig['com_rule'] == 0) { // Comments are deactivate
    die();
}

if ($xoopsModuleConfig['com_anonpost'] == 0 && !is_object($xoopsUser)) { // Anonymous users can't post
    die();
}
// ****************************************************************************

$com_itemid = isset($_GET['com_itemid']) ? (int)$_GET['com_itemid'] : 0;
if ($com_itemid > 0) {
    $article = new NewsStory($com_itemid);
    if ($article->storyid > 0) {
        $com_replytext = _POSTEDBY . '&nbsp;<b>' . $article->uname() . '</b>&nbsp;' . _DATE . '&nbsp;<b>' . formatTimestamp($article->published(), news_getmoduleoption('dateformat')) . '</b><br /><br />' . $article->hometext();
        $bodytext      = $article->bodytext();
        if ($bodytext != '') {
            $com_replytext .= '<br /><br />' . $bodytext . '';
        }
        $com_replytitle = $article->title();
        include_once XOOPS_ROOT_PATH . '/include/comment_new.php';
    } else {
        exit;
    }
}
