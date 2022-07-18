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

use XoopsModules\News;
use XoopsModules\News\Helper;
use XoopsModules\News\NewsStory;

/**
 * Display a block where news moderators can show news that needs to be moderated.
 */
function b_news_topics_moderate()
{
    // require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

    /** @var Helper $helper */
    if (!class_exists(Helper::class)) {
        return false;
    }

    $helper = Helper::getInstance();

    $block      = [];
    $dateformat = News\Utility::getModuleOption('dateformat');
    $infotips   = News\Utility::getModuleOption('infotips');

    $storyarray = NewsStory:: getAllSubmitted(0, true, News\Utility::getModuleOption('restrictindex'));
    if (count($storyarray) > 0) {
        $block['lang_story_title']  = _MB_TITLE;
        $block['lang_story_date']   = _MB_POSTED;
        $block['lang_story_author'] = _MB_POSTER;
        $block['lang_story_action'] = _MB_ACTION;
        $block['lang_story_topic']  = _MB_TOPIC;
        $myts                       = \MyTextSanitizer::getInstance();
        foreach ($storyarray as $newstory) {
            $title     = $newstory->title();
            $htmltitle = '';
            if ($infotips > 0) {
                $story['infotips'] = News\Utility::makeInfotips($newstory->hometext());
                $htmltitle         = ' title="' . $story['infotips'] . '"';
            }

            if (!isset($title) || ('' == $title)) {
                $linktitle = "<a href='" . XOOPS_URL . '/modules/news/index.php?op=edit&amp;storyid=' . $newstory->storyid() . "' target='_blank'" . $htmltitle . '>' . _MD_NEWS_NOSUBJECT . '</a>';
            } else {
                $linktitle = "<a href='" . XOOPS_URL . '/modules/news/submit.php?op=edit&amp;storyid=' . $newstory->storyid() . "' target='_blank'" . $htmltitle . '>' . $title . '</a>';
            }
            $story                = [];
            $story['title']       = $linktitle;
            $story['date']        = formatTimestamp($newstory->created(), $dateformat);
            $story['author']      = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $newstory->uid() . "'>" . $newstory->uname() . '</a>';
            $story['action']      = "<a href='" . XOOPS_URL . '/modules/news/admin/index.php?op=edit&amp;storyid=' . $newstory->storyid() . "'>" . _EDIT . "</a> - <a href='" . XOOPS_URL . '/modules/news/admin/index.php?op=delete&amp;storyid=' . $newstory->storyid() . "'>" . _MB_DELETE . '</a>';
            $story['topic_title'] = $newstory->topic_title();
            $story['topic_color'] = '#' . $myts->displayTarea($newstory->topic_color);
            $block['picture']     = XOOPS_URL . '/uploads/news/image/' . $newstory->picture();
            $block['pictureinfo'] = $newstory->pictureinfo();
            $block['stories'][]   = &$story;
            unset($story);
        }
    }

    return $block;
}

/**
 * @param $options
 */
function b_news_topics_moderate_onthefly($options): void
{
    $options = explode('|', $options);
    $block   = b_news_topics_moderate($options);

    $tpl = new \XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_moderate.tpl');
}
