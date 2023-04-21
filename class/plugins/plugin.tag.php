<?php declare(strict_types=1);

namespace XoopsModules\News;

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
 * @author         Hervé Thouzard  URL: https://www.herve-thouzard.com
 */

/**
 * @param $items
 *
 * @return bool|null
 */

use XoopsModules\News;

/**
 * @param $items
 * @return bool|null
 */
function news_tag_iteminfo(&$items): ?bool
{
    if (empty($items) || !\is_array($items)) {
        return false;
    }

    $items_id = [];
    foreach (\array_keys($items) as $cat_id) {
        foreach (\array_keys($items[$cat_id]) as $item_id) {
            $items_id[] = (int)$item_id;
        }
    }
    //    require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
    $tempNews  = new \XoopsModules\News\NewsStory();
    $items_obj = $tempNews->getStoriesByIds($items_id);

    foreach (\array_keys($items) as $cat_id) {
        foreach (\array_keys($items[$cat_id]) as $item_id) {
            if (isset($items_obj[$item_id])) {
                $item_obj                 = &$items_obj[$item_id];
                $items[$cat_id][$item_id] = [
                    'title'   => $item_obj->title(),
                    'uid'     => $item_obj->uid(),
                    'link'    => "article.php?storyid={$item_id}",
                    'time'    => $item_obj->published(),
                    'tags'    => '', // tag_parse_tag($item_obj->getVar("item_tags", "n")), // optional
                    'content' => '',
                ];
            }
        }
    }
    unset($items_obj);

    return null;
}

/**
 * @param $mid
 */
function news_tag_synchronization($mid): void
{
    global $xoopsDB;
    $itemHandler_keyName = 'storyid';
    $itemHandler_table   = $xoopsDB->prefix('news_stories');
    $linkHandler         = \XoopsModules\Tag\Helper::getInstance()->getHandler('Link'); //@var \XoopsModules\Tag\Handler $tagHandler
    $where               = "($itemHandler_table.published > 0 AND $itemHandler_table.published <= " . \time() . ") AND ($itemHandler_table.expired = 0 OR $itemHandler_table.expired > " . \time() . ')';

    /* clear tag-item links */
    if (version_compare($xoopsDB->getServerVersion(), '4.1.0', 'ge')) :
        $sql = "    DELETE FROM {$linkHandler->table}"
               . ' WHERE '
               . "     tag_modid = {$mid}"
               . '     AND '
               . '       ( tag_itemid NOT IN '
               . "           ( SELECT DISTINCT {$itemHandler_keyName} "
               . "             FROM {$itemHandler_table} "
               . "                WHERE $where"
               . '           ) '
               . '     )';
    else :
        $sql = "   DELETE {$linkHandler->table} FROM {$linkHandler->table}"
               . "  LEFT JOIN {$itemHandler_table} AS aa ON {$linkHandler->table}.tag_itemid = aa.{$itemHandler_keyName} "
               . '   WHERE '
               . "     tag_modid = {$mid}"
               . '     AND '
               . "       ( aa.{$itemHandler_keyName} IS NULL"
               . '           OR '
               . '       (aa.published > 0 AND aa.published <= '
               . \time()
               . ') AND (aa.expired = 0 OR aa.expired > '
               . \time()
               . ')'
               . '       )';

    endif;
    if (!$result = $linkHandler->db->queryF($sql)) {
        //xoops_error($linkHandler->db->error());
    }
}
