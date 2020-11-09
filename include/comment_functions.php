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
 * @param mixed $story_id
 * @param mixed $total_num
 */

// comment callback functions

// require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
use XoopsModules\News\NewsStory;

/**
 * @param $story_id
 * @param $total_num
 *
 * @return bool
 */
function news_com_update($story_id, $total_num)
{
    $story_id  = (int)$story_id;
    $total_num = (int)$total_num;
    $article   = new NewsStory($story_id);
    if (!$article->updateComments($total_num)) {
        return false;
    }

    return true;
}

/**
 * @param $comment
 */
function news_com_approve(&$comment)
{
    // notification mail here
}
