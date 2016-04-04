<?php
// 
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

/**
 * @param $options
 *
 * @return array
 */
function b_news_randomnews_show($options)
{
    include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    $myts          = MyTextSanitizer::getInstance();
    $block         = array();
    $block['sort'] = $options[0];

    $tmpstory   = new NewsStory;
    $restricted = news_getmoduleoption('restrictindex');
    $dateformat = news_getmoduleoption('dateformat');
    $infotips   = news_getmoduleoption('infotips');
    if ($dateformat == '') {
        $dateformat = 's';
    }
    if ($options[4] == 0) {
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, 0, 1, $options[0]);
    } else {
        $topics  = array_slice($options, 4);
        $stories = $tmpstory->getRandomNews($options[1], 0, $restricted, $topics, 1, $options[0]);
    }
    unset($tmpstory);
    if (count($stories) == 0) {
        return '';
    }
    foreach ($stories as $story) {
        $news  = array();
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
            $html             = $story->nohtml() == 1 ? 0 : 1;
            $news['teaser']   = news_truncate_tagsafe($myts->displayTarea($story->hometext, $html), $options[3] + 3);
            $news['infotips'] = ' title="' . $story->title() . '"';
        } else {
            $news['teaser'] = '';
            if ($infotips > 0) {
                $news['infotips'] = ' title="' . news_make_infotips($story->hometext()) . '"';
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
    if ($options[0] === 'published') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NEWS_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ($options[0] === 'counter') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NEWS_HITS . '</option>';

    $form .= "<option value='rating'";
    if ($options[0] === 'rating') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_NEWS_RATE . '</option>';

    $form .= "</select>\n";
    $form .= '&nbsp;' . _MB_NEWS_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'/>&nbsp;" . _MB_NEWS_ARTCLS;
    $form .= '&nbsp;<br><br />' . _MB_NEWS_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'/>&nbsp;" . _MB_NEWS_LENGTH . '<br /><br />';

    $form .= _MB_NEWS_TEASER . " <input type='text' name='options[]' value='" . $options[3] . "' />" . _MB_NEWS_LENGTH;
    $form .= '<br /><br />' . _MB_SPOTLIGHT_TOPIC . "<br /><select id='options[4]' name='options[]' multiple='multiple'>";

    include_once XOOPS_ROOT_PATH . '/modules/news/class/xoopsstory.php';
    $xt                    = new MyXoopsTopic($xoopsDB->prefix('news_topics'));
    $alltopics             = $xt->getTopicsList();
    $alltopics[0]['title'] = _MB_SPOTLIGHT_ALL_TOPICS;
    ksort($alltopics);
    $size = count($options);
    foreach ($alltopics as $topicid => $topic) {
        $sel = '';
        for ($i = 4; $i < $size; ++$i) {
            if ($options[$i] == $topicid) {
                $sel = " selected='selected'";
            }
        }
        $form .= "<option value='$topicid'$sel>" . $topic['title'] . '</option>';
    }
    $form .= '</select><br />';

    return $form;
}

/**
 * @param $options
 */
function b_news_randomnews_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &b_news_randomnews_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_moderate.tpl');
}
