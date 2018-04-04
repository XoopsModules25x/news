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

/*
 * Created on 5 nov. 2006
 *
 * This page is used to display a maps of the topics (with articles count)
 *
 * @package News
 * @author Hervé Thouzard
 * @copyright (c) Hervé Thouzard - http://www.herve-thouzard.com
 */

use XoopsModules\News;

include __DIR__ . '/../../mainfile.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
;

$GLOBALS['xoopsOption']['template_main'] = 'news_topics_directory.tpl';
require_once XOOPS_ROOT_PATH . '/header.php';

$myts = \MyTextSanitizer::getInstance();

$newscountbytopic = $tbl_topics = [];
$perms            = '';
$xt               = new NewsTopic();
$restricted       = News\Utility::getModuleOption('restrictindex');
if ($restricted) {
    global $xoopsUser;
    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler = xoops_getHandler('module');
    $newsModule    = $moduleHandler->getByDirname('news');
    $groups        = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $grouppermHandler  = xoops_getHandler('groupperm');
    $topics        = $grouppermHandler->getItemIds('news_view', $groups, $newsModule->getVar('mid'));
    if (count($topics) > 0) {
        $topics = implode(',', $topics);
        $perms  = ' AND topic_id IN (' . $topics . ') ';
    } else {
        return '';
    }
}
$topics_arr       = $xt->getChildTreeArray(0, 'topic_title', $perms);
$newscountbytopic = $xt->getNewsCountByTopic();
if (is_array($topics_arr) && count($topics_arr)) {
    foreach ($topics_arr as $onetopic) {
        $count = 0;
        if (array_key_exists($onetopic['topic_id'], $newscountbytopic)) {
            $count = $newscountbytopic[$onetopic['topic_id']];
        }
        if (0 != $onetopic['topic_pid']) {
            $onetopic['prefix'] = str_replace('.', '-', $onetopic['prefix']) . '&nbsp;';
        } else {
            $onetopic['prefix'] = str_replace('.', '', $onetopic['prefix']);
        }

        $tbl_topics[] = [
            'id'          => $onetopic['topic_id'],
            'news_count'  => $count,
            'topic_color' => '#' . $onetopic['topic_color'],
            'prefix'      => $onetopic['prefix'],
            'title'       => $myts->displayTarea($onetopic['topic_title'])
        ];
    }
}
$xoopsTpl->assign('topics', $tbl_topics);

$xoopsTpl->assign('advertisement', News\Utility::getModuleOption('advertisement'));

/**
 * Manage all the meta datas
 */
News\Utility::createMetaDatas();

$xoopsTpl->assign('xoops_pagetitle', _AM_NEWS_TOPICS_DIRECTORY);
$meta_description = _AM_NEWS_TOPICS_DIRECTORY . ' - ' . $xoopsModule->name('s');
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else { // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

require_once XOOPS_ROOT_PATH . '/footer.php';
