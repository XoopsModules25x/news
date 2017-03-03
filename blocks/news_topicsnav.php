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
 * @copyright      {@link http://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * @param $options
 *
 * @return array
 */
function b_news_topicsnav_show($options)
{
    include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
    $myts             = MyTextSanitizer::getInstance();
    $block            = array();
    $newscountbytopic = array();
    $perms            = '';
    $xt               = new NewsTopic();
    $restricted       = news_getmoduleoption('restrictindex');
    if ($restricted) {
        global $xoopsUser;
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $newsModule    = $moduleHandler->getByDirname('news');
        $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $gpermHandler = xoops_getHandler('groupperm');
        $topics        = $gpermHandler->getItemIds('news_view', $groups, $newsModule->getVar('mid'));
        if (count($topics) > 0) {
            $topics = implode(',', $topics);
            $perms  = ' AND topic_id IN (' . $topics . ') ';
        } else {
            return '';
        }
    }
    $topics_arr = $xt->getChildTreeArray(0, 'topic_title', $perms);
    if ($options[0] == 1) {
        $newscountbytopic = $xt->getNewsCountByTopic();
    }
    if (is_array($topics_arr) && count($topics_arr)) {
        foreach ($topics_arr as $onetopic) {
            if ($options[0] == 1) {
                $count = 0;
                if (array_key_exists($onetopic['topic_id'], $newscountbytopic)) {
                    $count = $newscountbytopic[$onetopic['topic_id']];
                }
            } else {
                $count = '';
            }
            $block['topics'][] = array(
                'id'          => $onetopic['topic_id'],
                'news_count'  => $count,
                'topic_color' => '#' . $onetopic['topic_color'],
                'title'       => $myts->displayTarea($onetopic['topic_title'])
            );
        }
    }

    return $block;
}

/**
 * @param $options
 *
 * @return string
 */
function b_news_topicsnav_edit($options)
{
    $form = _MB_NEWS_SHOW_NEWS_COUNT . " <input type='radio' name='options[]' value='1'";
    if ($options[0] == 1) {
        $form .= ' checked';
    }
    $form .= ' />' . _YES;
    $form .= "<input type='radio' name='options[]' value='0'";
    if ($options[0] == 0) {
        $form .= ' checked';
    }
    $form .= ' />' . _NO;

    return $form;
}

/**
 * @param $options
 */
function b_news_topicsnav_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &b_news_topicsnav_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_topicnav.tpl');
}
