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

/**
 * RSS per topics
 *
 * This script is used to generate RSS feeds for each topic.
 * You can enable and disable this feature with the module's option named "Enable RSS feeds per topics?"
 * The script uses the permissions to know what to display.
 *
 * @param type $nomvariable description
 * @author        Xoops Modules Dev Team
 * @copyright (c) XOOPS Project (https://xoops.org)
 */

use Xmf\Request;
use XoopsModules\News\{
    NewsStory,
    NewsTopic,
    Utility
};

require_once \dirname(__DIR__, 2) . '/mainfile.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

if (!Utility::getModuleOption('topicsrss')) {
    exit();
}

$topicid = Request::getInt('topicid', 0, 'GET');
if (0 == $topicid) {
    exit();
}

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}

$restricted = Utility::getModuleOption('restrictindex');
$newsnumber = Utility::getModuleOption('storyhome');

$charset = 'utf-8';

header('Content-Type:text/xml; charset=' . $charset);
$story        = new NewsStory();
$tpl          = new \XoopsTpl();
$tpl->caching = 2;
$tpl->cache_lifetime=3600; // Change this to the value you want
if (!$tpl->isCached('db:news_rss.tpl', $topicid)) {
    $xt     = new NewsTopic($topicid);
    $sarray = NewsStory::getAllPublished($newsnumber, 0, $restricted, $topicid);
    if ($sarray && \is_array($sarray)) {
        $sitename = htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES | ENT_HTML5);
        $slogan   = htmlspecialchars($xoopsConfig['slogan'], ENT_QUOTES | ENT_HTML5);
        $tpl->assign('channel_title', xoops_utf8_encode($sitename));
        $tpl->assign('channel_link', XOOPS_URL . '/');
        $tpl->assign('channel_desc', xoops_utf8_encode($slogan));
        $tpl->assign('channel_lastbuild', formatTimestamp(time(), 'rss'));
        $tpl->assign('channel_webmaster', checkEmail($xoopsConfig['adminmail'], true)); // Fed up with spam
        $tpl->assign('channel_editor', checkEmail($xoopsConfig['adminmail'], true)); // Fed up with spam
        $tpl->assign('channel_category', $xt->topic_title());
        $tpl->assign('channel_generator', 'XOOPS');
        $tpl->assign('channel_language', _LANGCODE);
        $tpl->assign('image_url', XOOPS_URL . '/images/logo.gif');
        $dimention = getimagesize(XOOPS_ROOT_PATH . '/images/logo.gif');
        if (empty($dimention[0])) {
            $width = 88;
        } else {
            $width = ($dimention[0] > 144) ? 144 : $dimention[0];
        }
        if (empty($dimention[1])) {
            $height = 31;
        } else {
            $height = ($dimention[1] > 400) ? 400 : $dimention[1];
        }
        $tpl->assign('image_width', $width);
        $tpl->assign('image_height', $height);
        $count = $sarray;
        foreach ($sarray as $story) {
            $storytitle = $story->title();
            //if we are allowing html, we need to use htmlspecialchars or any bug will break the output
            $description = htmlspecialchars($story->hometext(), ENT_QUOTES | ENT_HTML5);
            $tpl->append(
                'items',
                [
                    'title'       => xoops_utf8_encode($storytitle),
                    'link'        => XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(),
                    'guid'        => XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(),
                    'pubdate'     => formatTimestamp($story->published(), 'rss'),
                    'description' => xoops_utf8_encode($description),
                ]
            );
        }
    }
}
$tpl->display('db:news_rss.tpl', $topicid);
