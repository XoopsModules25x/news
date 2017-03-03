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
 * @return mixed
 */
function b_news_topics_show()
{
    global $storytopic; // Don't know why this is used and where it's coming from ....
    include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/tree.php';

    $jump       = XOOPS_URL . '/modules/news/index.php?storytopic=';
    $storytopic = !empty($storytopic) ? (int)$storytopic : 0;
    $restricted = news_getmoduleoption('restrictindex');

    $xt                 = new NewsTopic();
    $allTopics          = $xt->getAllTopics($restricted);
    $topic_tree         = new MyXoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
    $additional         = " onchange='location=\"" . $jump . "\"+this.options[this.selectedIndex].value'";
    $block['selectbox'] = $topic_tree->makeSelBox('storytopic', 'topic_title', '-- ', '', true, 0, $additional);

    return $block;
}

/**
 * @param $options
 */
function b_news_topics_onthefly($options)
{
    $options = explode('|', $options);
    $block   =& b_news_topics_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_topics.tpl');
}
