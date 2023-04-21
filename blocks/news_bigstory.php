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
use XoopsModules\News\Utility;

/**
 * @return array|null|false
 */
function b_news_bigstory_show()
{
    // require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

    /** @var Helper $helper */
    if (!class_exists(Helper::class)) {
        return [];
    }

    $helper = Helper::getInstance();

    $myts       = \MyTextSanitizer::getInstance();
    $restricted = Utility::getModuleOption('restrictindex');
    $dateformat = Utility::getModuleOption('dateformat');
    $infotips   = Utility::getModuleOption('infotips');

    $block    = [];
    $onestory = new NewsStory();
    $stories  = $onestory->getBigStory(1, 0, $restricted, 0, 1, true, 'counter');
    if (0 == count($stories)) {
        $block['message'] = _MB_NEWS_NOTYET;
    } else {
        foreach ($stories as $key => $story) {
            $htmltitle = '';
            if ($infotips > 0) {
                $block['infotips'] = Utility::makeInfotips($story->hometext());
                $htmltitle         = ' title="' . $block['infotips'] . '"';
            } else {
                $htmltitle = ' title="' . $story->title('Show') . '"';
            }
            $block['htmltitle']         = $htmltitle;
            $block['message']           = _MB_NEWS_TMRSI;
            $block['story_title']       = $story->title('Show');
            $block['story_id']          = $story->storyid();
            $block['story_date']        = formatTimestamp($story->published(), $dateformat);
            $block['story_hits']        = $story->counter();
            $block['story_rating']      = $story->rating();
            $block['story_votes']       = $story->votes();
            $block['story_author']      = $story->uname();
            $block['story_text']        = $story->hometext();
            $block['story_topic_title'] = $story->topic_title();
            $block['story_topic_color'] = '#' . $myts->displayTarea($story->topic_color);
            $block['story_picture']     = XOOPS_URL . '/uploads/news/image/' . $story->picture();
            $block['story_pictureinfo'] = $story->pictureinfo();
        }
    }

    return $block;
}

/**
 * @param $options
 */
function b_news_bigstory_onthefly($options): void
{
    $options = explode('|', $options);
    $block   = b_news_bigstory_show($options);

    $tpl = new \XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_bigstory.tpl');
}
