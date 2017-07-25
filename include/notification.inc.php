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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $category
 * @param $item_id
 *
 * @return null
 */
function news_notify_iteminfo($category, $item_id)
{
    if ($category === 'global') {
        $item['name'] = '';
        $item['url']  = '';

        return $item;
    }

    global $xoopsDB;

    if ($category === 'story') {
        // Assume we have a valid story id
        $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('news_stories') . ' WHERE storyid = ' . (int)$item_id;
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['title'];
            $item['url']  = XOOPS_URL . '/modules/news/article.php?storyid=' . (int)$item_id;

            return $item;
        } else {
            return null;
        }
    }

    // Added by Lankford on 2007/3/23
    if ($category === 'category') {
        $sql    = 'SELECT title FROM ' . $xoopsDB->prefix('news_topics') . ' WHERE topic_id = ' . (int)$item_id;
        $result = $xoopsDB->query($sql);
        if ($result) {
            $result_array = $xoopsDB->fetchArray($result);
            $item['name'] = $result_array['topic_id'];
            $item['url']  = XOOPS_URL . '/modules/news/index.php?storytopic=' . (int)$item_id;

            return $item;
        } else {
            return null;
        }
    }

    return null;
}
