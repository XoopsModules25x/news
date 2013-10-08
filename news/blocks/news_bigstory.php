<?php
// $Id: news_bigstory.php 12097 2013-09-26 15:56:34Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

function b_news_bigstory_show()
{
    include_once XOOPS_ROOT_PATH.'/modules/news/include/functions.php';
    include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';
    $myts =& MyTextSanitizer::getInstance();
    $restricted=news_getmoduleoption('restrictindex');
    $dateformat=news_getmoduleoption('dateformat');
    $infotips=news_getmoduleoption('infotips');

    $block = array();
    $onestory = new NewsStory();
    $stories = $onestory->getBigStory(1,0,$restricted,0,1, true, 'counter');
    if (count($stories)==0) {
        $block['message'] = _MB_NEWS_NOTYET;
    } else {
        foreach ($stories as $key => $story) {
            $htmltitle='';
            if ($infotips>0) {
                $block['infotips'] = news_make_infotips($story->hometext());
                $htmltitle=' title="'.$block['infotips'].'"';
            } else {
                $htmltitle=' title="'.$story->title('Show').'"';
            }
            $block['htmltitle']=$htmltitle;
            $block['message'] = _MB_NEWS_TMRSI;
            $block['story_title'] = $story->title('Show');
            $block['story_id'] = $story->storyid();
            $block['story_date'] = formatTimestamp($story->published(), $dateformat);
            $block['story_hits'] = $story->counter();
         $block['story_rating'] = $story->rating();
         $block['story_votes'] = $story->votes();
         $block['story_author']= $story->uname();
         $block['story_text']= $story->hometext();
         $block['story_topic_title']= $story->topic_title();
         $block['story_topic_color']= '#'.$myts->displayTarea($story->topic_color);
         $block['story_picture'] = XOOPS_URL.'/uploads/news/image/'.$story->picture();
         $block['story_pictureinfo'] = $story->pictureinfo();
        }
    }

    return $block;
}

function b_news_bigstory_onthefly($options)
{
    $options = explode('|',$options);
    $block = & b_news_bigstory_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_bigstory.html');
}
