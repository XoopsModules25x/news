<?php
// $Id: notification.inc.php 12097 2013-09-26 15:56:34Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
if (!defined('XOOPS_ROOT_PATH')) {
    die("XOOPS root path not defined");
}

function news_notify_iteminfo($category, $item_id)
{
    if ($category == 'global') {
        $item['name'] = '';
        $item['url'] = '';

        return $item;
    }

    global $xoopsDB;

    if ($category=='story') {
        // Assume we have a valid story id
        $sql = 'SELECT title FROM '.$xoopsDB->prefix('mod_news_stories') . ' WHERE storyid = ' . intval($item_id);
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url'] = XOOPS_URL . '/modules/news/article.php?storyid=' . intval($item_id);

            return $item;
        } else {
            return null;
        }
    }

    // Added by Lankford on 2007/3/23
    if ($category=='category') {
        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('mod_news_topics') . ' WHERE topic_id = '.intval($item_id);
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['topic_id'];
            $item['url'] = XOOPS_URL . '/modules/news/index.php?storytopic=' . intval($item_id);

            return $item;
        } else {
            return null;
        }
    }
}
