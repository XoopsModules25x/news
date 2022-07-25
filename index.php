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
 * Module's index
 *
 * This page displays a list of the published articles and can also display the
 * stories of a particular topic.
 *
 * @author                Xoops Modules Dev Team
 * @copyright (c)         XOOPS Project (https://xoops.org)
 *
 * Parameters received by this page :
 * @page_param            int        storytopic                    Topic's ID
 * @page_param            int        topic_id                    Topic's ID
 * @page_param            int        storynum                    Number of news per page
 * @page_param            int        start                        First news to display
 *
 * @page_title            Topic's title - Story's title - Module's name
 *
 * @template_name         news_index.html or news_by_topic.html
 *
 * Template's variables :
 * For each article
 * @template_var          int        id            story's ID
 * @template_var          string    poster        Complete link to the author's profile
 * @template_var          string    author_name    Author's name according to the module's option called displayname
 * @template_var          int        author_uid    Author's ID
 * @template_var          float    rating        New's rating
 * @template_var          int        votes        number of votes
 * @template_var          int        posttimestamp Timestamp representing the published date
 * @template_var          string    posttime        Formated published date
 * @template_var          string    text        The introduction's text
 * @template_var          string    morelink    The link to read the full article (points to article.php)
 * @template_var          string    adminlink    Link reserved to the admin to edit and delete the news
 * @template_var          string    mail_link    Link used to send the story's url by email
 * @template_var          string    title        Story's title presented on the form of a link
 * @template_var          string    news_title    Just the news title
 * @template_var          string    topic_title    Just the topic's title
 * @template_var          int        hits        Number of times the article was read
 * @template_var          int        files_attached    Number of files attached to this news
 * @template_var          string    attached_link    An URL pointing to the attached files
 * @template_var          string    topic_color    The topic's color
 * @template_var          int        columnwidth    column's width
 * @template_var          int        displaynav    To know if we must display the navigation's box
 * @template_var          string    lang_go        fixed text : Go!
 * @template_var          string    lang_morereleases    fixed text : More releases in
 * @template_var          string    lang_on        fixed text : on
 * @template_var          string    lang_postedby    fixed text : Posted by
 * @template_var          string    lang_printerpage    fixed text : Printer Friendly Page
 * @template_var          string    lang_ratethisnews    fixed text : Rate this News
 * @template_var          string    lang_ratingc    fixed text : Rating:
 * @template_var          string    lang_reads        fixed text : reads
 * @template_var          string    lang_sendstory    fixed text : Send this Story to a Friend
 * @template_var          string     topic_select    contains the topics selector
 */

use Xmf\Module\Admin;
use Xmf\Request;
use XoopsModules\News;
use XoopsModules\News\Files;
use XoopsModules\News\NewsStory;
use XoopsModules\News\NewsTopic;

require_once \dirname(__DIR__, 2) . '/mainfile.php';

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();

//$XOOPS_URL = XOOPS_URL;
//$u=$XOOPS_URL.'/uploads/news_xml.php';
//  $x = file_get_contents($u);

//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
//;
//require_once XOOPS_ROOT_PATH . '/modules/news/class/tree.php';

$moduleDirName = basename(__DIR__);
xoops_load('utility', $moduleDirName);
$module = \XoopsModule::getByDirname($moduleDirName);

$storytopic = 0;
if (Request::hasVar('storytopic', 'GET')) {
    $storytopic = Request::getInt('storytopic', 0, 'GET');
} elseif (Request::hasVar('topic_id', 'GET')) {
    $storytopic = Request::getInt('topic_id', 0, 'GET');
}

if ($storytopic) {
    $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    if (!$grouppermHandler->checkRight('news_view', $storytopic, $groups, $xoopsModule->getVar('mid'))) {
        redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
    }
    $xoopsOption['storytopic'] = $storytopic;
} else {
    $xoopsOption['storytopic'] = 0;
}
if (Request::hasVar('storynum', 'GET')) {
    $xoopsOption['storynum'] = Request::getInt('storynum', 0, 'GET');
    if ($xoopsOption['storynum'] > 30) {
        $xoopsOption['storynum'] = $helper->getConfig('storyhome');
    }
} else {
    $xoopsOption['storynum'] = $helper->getConfig('storyhome');
}

if (Request::hasVar('start', 'GET')) {
    $start = Request::getInt('start', 0, 'GET');
} else {
    $start = 0;
}

if (empty($helper->getConfig('newsdisplay')) || 'Classic' === $helper->getConfig('newsdisplay')
    || $xoopsOption['storytopic'] > 0) {
    $showclassic = 1;
} else {
    $showclassic = 0;
}
$firsttitle = '';
$topictitle = '';
$myts       = \MyTextSanitizer::getInstance();
$sfiles     = new Files();

$column_count = $helper->getConfig('columnmode');

if ($showclassic) {
    $GLOBALS['xoopsOption']['template_main'] = 'news_index.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $xt = new NewsTopic();

    $xoopsTpl->assign('columnwidth', (int)(1 / $column_count * 100));
    if ($helper->getConfig('ratenews')) {
        $xoopsTpl->assign('rates', true);
        $xoopsTpl->assign('lang_ratingc', _NW_RATINGC);
        $xoopsTpl->assign('lang_ratethisnews', _NW_RATETHISNEWS);
    } else {
        $xoopsTpl->assign('rates', false);
    }

    if ($xoopsOption['storytopic']) {
        $xt->getTopic($xoopsOption['storytopic']);
        $xoopsTpl->assign('topic_description', $xt->topic_description('S'));
        $xoopsTpl->assign('topic_color', '#' . $xt->topic_color('S'));
        $topictitle = $xt->topic_title();
    }

    if (1 == $helper->getConfig('displaynav')) {
        $xoopsTpl->assign('displaynav', true);

        $allTopics  = $xt->getAllTopics($helper->getConfig('restrictindex'));
        $topic_tree = new \XoopsModules\News\ObjectTree($allTopics, 'topic_id', 'topic_pid');

        if (News\Utility::checkVerXoops($GLOBALS['xoopsModule'], '2.5.9')) {
            $topic_select = $topic_tree->makeSelectElement('storytopic', 'topic_title', '--', $xoopsOption['storytopic'], true, 0, '', '');
            $xoopsTpl->assign('topic_select', $topic_select->render());
        } else {
            $topic_select = $topic_tree->makeSelBox('storytopic', 'topic_title', '-- ', $xoopsOption['storytopic'], true);
            $xoopsTpl->assign('topic_select', $topic_select);
        }

        $storynum_options = '';
        for ($i = 5; $i <= 30; $i += 5) {
            $sel = '';
            if ($i == $xoopsOption['storynum']) {
                $sel = ' selected';
            }
            $storynum_options .= '<option value="' . $i . '"' . $sel . '>' . $i . '</option>';
        }
        $xoopsTpl->assign('storynum_options', $storynum_options);
    } else {
        $xoopsTpl->assign('displaynav', false);
    }
    if (0 == $xoopsOption['storytopic']) {
        $topic_frontpage = true;
    } else {
        $topic_frontpage = false;
    }
    $sarray = NewsStory::getAllPublished($xoopsOption['storynum'], $start, $helper->getConfig('restrictindex'), $xoopsOption['storytopic'], 0, true, 'published', $topic_frontpage);

    $scount = count($sarray);
    $xoopsTpl->assign('story_count', $scount);
    $k       = 0;
    $columns = [];
    if ($scount > 0) {
        $storieslist = [];
        foreach ($sarray as $storyid => $thisstory) {
            $storieslist[] = $thisstory->storyid();
        }
        $filesperstory = $sfiles->getCountbyStories($storieslist);

        if (!empty($sarray)) {
            foreach ($sarray as $storyid => $thisstory) {
                $filescount = array_key_exists($thisstory->storyid(), $filesperstory) ? $filesperstory[$thisstory->storyid()] : 0;
                $story      = $thisstory->prepare2show($filescount);
                // The line below can be used to display a Permanent Link image
                // $story['title'] .= "&nbsp;&nbsp;<a href='".XOOPS_URL."/modules/news/article.php?storyid=".$sarray[$i]->storyid()."'><img src='".XOOPS_URL."/modules/news/assets/images/x.gif' alt='Permanent Link'></a>";
                $story['news_title']  = $story['title'];
                $story['title']       = $thisstory->textlink() . '&nbsp;:&nbsp;' . $story['title'];
                $story['topic_title'] = $thisstory->textlink();
                $story['topic_img'] = $thisstory->imglink();
                $story['topic_color'] = '#' . $myts->displayTarea($thisstory->topic_color);
                if ('' === $firsttitle) {
                    $firsttitle = $thisstory->topic_title() . ' - ' . $thisstory->title();
                }
                $columns[$k][] = $story;
                ++$k;
                if ($k == $column_count) {
                    $k = 0;
                }
            }
        }
    }
    $xoopsTpl->assign('columns', $columns);
    unset($story);

    // orwah show topictitle in news_item.tpl
    if (1 == News\Utility::getModuleOption('displaytopictitle')) {
        $xoopsTpl->assign('displaytopictitle', true);
    } else {
        $xoopsTpl->assign('displaytopictitle', false);
    }

    $totalcount = NewsStory::countPublishedByTopic($xoopsOption['storytopic'], $helper->getConfig('restrictindex'));
    if ($totalcount > $scount) {
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new \XoopsPageNav($totalcount, $xoopsOption['storynum'], $start, 'start', 'storytopic=' . $xoopsOption['storytopic']);
        if (News\Utility::isBot()) { // A bot is reading the news, we are going to show it all the links so that it can read everything
            $xoopsTpl->assign('pagenav', $pagenav->renderNav($totalcount));
        } else {
            $xoopsTpl->assign('pagenav', $pagenav->renderNav());
        }
    } else {
        $xoopsTpl->assign('pagenav', '');
    }
} else { // Affichage par sujets
    $GLOBALS['xoopsOption']['template_main'] = 'news_by_topic.tpl';
    require_once XOOPS_ROOT_PATH . '/header.php';
    $xoopsTpl->assign('columnwidth', (int)(1 / $column_count * 100));
    if ($helper->getConfig('ratenews')) {
        $xoopsTpl->assign('rates', true);
        $xoopsTpl->assign('lang_ratingc', _NW_RATINGC);
        $xoopsTpl->assign('lang_ratethisnews', _NW_RATETHISNEWS);
    } else {
        $xoopsTpl->assign('rates', false);
    }

    $xt            = new NewsTopic();
    $alltopics     = $xt->getTopicsList(true, $helper->getConfig('restrictindex'));
    $smarty_topics = [];
    $topicstories  = [];

    foreach ($alltopics as $topicid => $topic) {
        $allstories  = NewsStory::getAllPublished($helper->getConfig('storyhome'), 0, $helper->getConfig('restrictindex'), $topicid);
        $storieslist = [];
        foreach ($allstories as $thisstory) {
            $storieslist[] = $thisstory->storyid();
        }
        $filesperstory = $sfiles->getCountbyStories($storieslist);
        foreach ($allstories as $thisstory) {
            $filescount               = array_key_exists($thisstory->storyid(), $filesperstory) ? $filesperstory[$thisstory->storyid()] : 0;
            $story                    = $thisstory->prepare2show($filescount);
            $story['topic_title']     = $thisstory->textlink();
            $story['news_title']      = $story['title'];
            $topicstories[$topicid][] = $story;
        }
        if (isset($topicstories[$topicid])) {
            $smarty_topics[$topicstories[$topicid][0]['posttimestamp']] = [
                'title'       => $topic['title'],
                'stories'     => $topicstories[$topicid],
                'id'          => $topicid,
                'topic_color' => $topic['color'],
            ];
        }
    }

    krsort($smarty_topics);
    $columns = [];
    $i       = 0;
    foreach ($smarty_topics as $thistopictimestamp => $thistopic) {
        $columns[$i][] = $thistopic;
        ++$i;
        if ($i == $column_count) {
            $i = 0;
        }
    }
    //$xoopsTpl->assign('topics', $smarty_topics);
    $xoopsTpl->assign('columns', $columns);
}

$xoopsTpl->assign('advertisement', News\Utility::getModuleOption('advertisement'));

/**
 * Create the Meta Datas
 */
News\Utility::createMetaDatas();

/**
 * Create a clickable path from the root to the current topic (if we are viewing a topic)
 * Actually this is not used in the default templates but you can use it as you want
 * You can comment the code to optimize the requests count
 */
if ($xoopsOption['storytopic']) {
    // require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopstree.php';
    $mytree    = new News\XoopsTree($xoopsDB->prefix('news_topics'), 'topic_id', 'topic_pid');
    $topicpath = $mytree->getNicePathFromId($xoopsOption['storytopic'], 'topic_title', 'index.php?op=1');
    $xoopsTpl->assign('topic_path', $topicpath);
    unset($mytree);
}

/**
 * Create a link for the RSS feed (if the module's option is activated)
 */
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$moduleInfo    = $moduleHandler->get($GLOBALS['xoopsModule']->getVar('mid'));
if ($helper->getConfig('topicsrss') && $xoopsOption['storytopic']) {
    $link = sprintf("<a href='%s' title='%s'><img src='%s' border='0' alt='%s'></a>", XOOPS_URL . '/modules/news/backendt.php?topicid=' . $xoopsOption['storytopic'], _NW_RSSFEED, Admin::iconUrl('', 16) . '/rss.gif', _NW_RSSFEED);
    $xoopsTpl->assign('topic_rssfeed_link', $link);
}

/**
 * Assign page's title
 */
if ('' !== $firsttitle) {
    $xoopsTpl->assign('xoops_pagetitle', $firsttitle . ' - ' . $xoopsModule->name('s'));
} elseif ('' !== $topictitle) {
    $xoopsTpl->assign('xoops_pagetitle', $topictitle);
} else {
    $xoopsTpl->assign('xoops_pagetitle', $xoopsModule->name('s'));
}

$xoopsTpl->assign('lang_go', _GO);
$xoopsTpl->assign('lang_on', _ON);
$xoopsTpl->assign('lang_printerpage', _NW_PRINTERFRIENDLY);
$xoopsTpl->assign('lang_sendstory', _NW_SENDSTORY);
$xoopsTpl->assign('lang_postedby', _POSTEDBY);
$xoopsTpl->assign('lang_reads', _READS);
$xoopsTpl->assign('lang_morereleases', _NW_MORERELEASES);
require_once XOOPS_ROOT_PATH . '/footer.php';
