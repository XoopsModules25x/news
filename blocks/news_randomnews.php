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


use XoopsModules\News;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');

// require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

/**
 * @param $options
 *
 * @return array|string
 */
function b_news_randomnews_show($options)
{
    ;
    $myts          = \MyTextSanitizer::getInstance();
    $block         = [];
    $block['sort'] = $options[0];

    $tmpstory   = new NewsStory;
    $restricted = News\Utility::getModuleOption('restrictindex');
    $dateformat = News\Utility::getModuleOption('dateformat');
    $infotips   = News\Utility::getModuleOption('infotips');
    if ('' == $dateformat) {
        $dateformat = 's';
    }
    if (0 == $options[4]) {
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, 0, 1, $options[0]);
    } else {
        $topics  = array_slice($options, 4);
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, $topics, 1, $options[0]);
    }
    unset($tmpstory);
    if (0 == count($stories)) {
        return '';
    }
    foreach ($stories as $story) {
        $news  = [];
        $title = $story->title();
        if (strlen($title) > $options[2]) {
            $title = xoops_substr($title, 0, $options[2] + 3);
        }
        $news['title']       = $title;
        $news['id']          = $story->storyid();
        $news['date']        = formatTimestamp($story->published(), $dateformat);
        $news['hits']        = $story->counter();
        $news['rating']      = $story->rating();
        $news['votes']       = $story->votes();
        $news['author']      = sprintf('%s %s', _POSTEDBY, $story->uname());
        $news['topic_title'] = $story->topic_title();
        $news['topic_color'] = '#' . $myts->displayTarea($story->topic_color);
        $news['picture']     = XOOPS_URL . '/uploads/news/image/' . $story->picture();
        $news['pictureinfo'] = $story->pictureinfo();

        if ($options[3] > 0) {
            $html             = 1 == $story->nohtml() ? 0 : 1;
            $news['teaser']   = News\Utility::truncateTagSafe($myts->displayTarea($story->hometext, $html), $options[3] + 3);
            $news['infotips'] = ' title="' . $story->title() . '"';
        } else {
            $news['teaser'] = '';
            if ($infotips > 0) {
                $news['infotips'] = ' title="' . News\Utility::makeInfotips($story->hometext()) . '"';
            } else {
                $news['infotips'] = ' title="' . $story->title() . '"';
            }
        }
        $block['stories'][] = $news;
    }
    $block['lang_read_more'] = _MB_READMORE;

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function b_news_randomnews_edit($options)
{
    global $xoopsDB;
    $form = _MB_NEWS_ORDER . "&nbsp;<select name='options[]'>";
    $form .= "<option value='published'";
    if ('published' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ('counter' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_HITS . '</option>';

    $form .= "<option value='rating'";
    if ('rating' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_RATE . '</option>';

    $form .= "</select>\n";
    $form .= '&nbsp;' . _MB_NEWS_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_NEWS_ARTCLS;
    $form .= '&nbsp;<br><br>' . _MB_NEWS_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_NEWS_LENGTH . '<br><br>';

    $form .= _MB_NEWS_TEASER . " <input type='text' name='options[]' value='" . $options[3] . "'>" . _MB_NEWS_LENGTH;
    $form .= '<br><br>' . _MB_SPOTLIGHT_TOPIC . "<br><select id='options[4]' name='options[]' multiple='multiple'>";

    // require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopsstory.php';
    $xt                    = new \XoopsTopic($xoopsDB->prefix('news_topics'));
    $alltopics             = $xt->getTopicsList();
    $alltopics[0]['title'] = _MB_SPOTLIGHT_ALL_TOPICS;
    ksort($alltopics);
    $size = count($options);
    foreach ($alltopics as $topicid => $topic) {
        $sel = '';
        for ($i = 4; $i < $size; ++$i) {
            if ($options[$i] == $topicid) {
                $sel = ' selected';
            }
        }
        $form .= "<option value='$topicid'$sel>" . $topic['title'] . '</option>';
    }
    $form .= '</select><br>';

    return $form;
}

/**
 * @param $options
 */
function b_news_randomnews_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &b_news_randomnews_show($options);

    $tpl = new \XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_moderate.tpl');
}
