<?php
// $Id$
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
/*
 * Enable users to note a news
 *
 * This page is called from the page "article.php" and "index.php", it
 * enables users to vote for a news, according to the module's option named
 * "ratenews". This code is *heavily* based on the file "ratefile.php" from
 * the mydownloads module.
 * Possible hack : Enable only registred users to vote
 * Notes :
 *          Anonymous users can only vote 1 time per day (except if their IP change)
 *          Author's can't vote for their own news
 *          Registred users can only vote one time
 *
 * @package News
 * @author Xoops Modules Dev Team
 * @copyright   (c) XOOPS Project (http://xoops.org)
 *
 * Parameters received by this page :
 * @page_param  int     storyid Id of the story we are going to vote for
 * @page_param  string  submit  The submit button of the rating form
 * @page_param  int     rating  User's rating
 *
 * @page_title          Story's title - "Rate this news" - Module's name
 *
 * @template_name       news_ratenews.html
 *
 * Template's variables :
 * @template_var    string  lang_voteonce   Fixed text "Please do not vote for the same resource more than once."
 * @template_var    string  lang_ratingscale    Fixed text "The scale is 1 - 10, with 1 being poor and 10 being excellent."
 * @template_var    string  lang_beobjective    Fixed text "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful."
 * @template_var    string  lang_donotvote      Fixed text "Do not vote for your own resource."
 * @template_var    string  lang_rateit         Fixed text "Rate It!"
 * @template_var    string  lang_cancel         Fixed text "Cancel"
 * @template_var    array   news                Contains some information about the story
 *                                  Structure :
 * @template_var                    int     storyid     Story's ID
 * @template_var                    string  title       story's title
 */
include_once __DIR__ . '/header.php';
include_once XOOPS_ROOT_PATH . '/class/module.errorhandler.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/modules/news/config.php';
$myts = MyTextSanitizer::getInstance();

// Verify the perms
// 1) Is the vote activated in the module ?
$ratenews = news_getmoduleoption('ratenews');
if (!$ratenews) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}

// Limit rating by registred users
if ($cfg['config_rating_registred_only']) {
    if (!isset($xoopsUser) || !is_object($xoopsUser)) {
        redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
    }
}

// 2) Is the story published ?
$storyid = 0;
if (isset($_GET['storyid'])) {
    $storyid = (int)$_GET['storyid'];
} else {
    if (isset($_POST['storyid'])) {
        $storyid = (int)$_POST['storyid'];
    }
}

if (!empty($storyid)) {
    $article = new NewsStory($storyid);
    if ($article->published() == 0 || $article->published() > time()) {
        redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
    }

    // Expired
    if ($article->expired() != 0 && $article->expired() < time()) {
        redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
    }
} else {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

// 3) Does the user can see this news ? If he can't see it, he can't vote for
$gperm_handler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gperm_handler->checkRight('news_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}

if (!empty($_POST['submit'])) { // The form was submited
    $eh = new ErrorHandler; //ErrorHandler object
    if (!is_object($xoopsUser)) {
        $ratinguser = 0;
    } else {
        $ratinguser = $xoopsUser->getVar('uid');
    }

    //Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip           = getenv('REMOTE_ADDR');
    $storyid      = (int)$_POST['storyid'];
    $rating       = (int)$_POST['rating'];

    // Check if Rating is Null
    if ($rating == '--') {
        redirect_header(XOOPS_URL . '/modules/news/ratenews.php?storyid=' . $storyid, 4, _NW_NORATING);
    }

    if ($rating < 1 || $rating > 10) {
        die(_ERRORS);
    }

    // Check if News POSTER is voting (UNLESS Anonymous users allowed to post)
    if ($ratinguser != 0) {
        $result = $xoopsDB->query('SELECT uid FROM ' . $xoopsDB->prefix('news_stories') . " WHERE storyid=$storyid");
        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header(XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid, 4, _NW_CANTVOTEOWN);
            }
        }

        // Check if REG user is trying to vote twice.
        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('news_stories_votedata') . " WHERE storyid=$storyid");
        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header(XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid, 4, _NW_VOTEONCE);
            }
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $result    = $xoopsDB->query('SELECT COUNT(*) FROM '
                                     . $xoopsDB->prefix('news_stories_votedata')
                                     . " WHERE storyid=$storyid AND ratinguser=0 AND ratinghostname = '$ip'  AND ratingtimestamp > $yesterday");
        list($anonvotecount) = $xoopsDB->fetchRow($result);
        if ($anonvotecount >= 1) {
            redirect_header(XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid, 4, _NW_VOTEONCE);
        }
    }

    //All is well.  Add to Line Item Rate to DB.
    $newid    = $xoopsDB->genId($xoopsDB->prefix('news_stories_votedata') . '_ratingid_seq');
    $datetime = time();
    $sql      = sprintf("INSERT INTO %s (ratingid, storyid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)",
                        $xoopsDB->prefix('news_stories_votedata'), $newid, $storyid, $ratinguser, $rating, $ip, $datetime);
    $xoopsDB->query($sql) || ErrorHandler::show('0013');

    //All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
    news_updaterating($storyid);
    $ratemessage = _NW_VOTEAPPRE . '<br>' . sprintf(_NW_THANKYOU, $xoopsConfig['sitename']);
    redirect_header(XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid, 4, $ratemessage);
} else { // Display the form to vote
    $GLOBALS['xoopsOption']['template_main'] = 'news_ratenews.tpl';
    include_once XOOPS_ROOT_PATH . '/header.php';
    $news = null;
    $news = new NewsStory($storyid);
    if (is_object($news)) {
        $title = $news->title('Show');
    } else {
        redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _ERRORS);
    }
    $xoopsTpl->assign('advertisement', news_getmoduleoption('advertisement'));
    $xoopsTpl->assign('news', array('storyid' => $storyid, 'title' => $title));
    $xoopsTpl->assign('lang_voteonce', _NW_VOTEONCE);
    $xoopsTpl->assign('lang_ratingscale', _NW_RATINGSCALE);
    $xoopsTpl->assign('lang_beobjective', _NW_BEOBJECTIVE);
    $xoopsTpl->assign('lang_donotvote', _NW_DONOTVOTE);
    $xoopsTpl->assign('lang_rateit', _NW_RATEIT);
    $xoopsTpl->assign('lang_cancel', _CANCEL);
    $xoopsTpl->assign('xoops_pagetitle', $title . ' - ' . _NW_RATETHISNEWS . ' - ' . $xoopsModule->name('s'));
    news_CreateMetaDatas();
    include_once XOOPS_ROOT_PATH . '/footer.php';
}
include_once XOOPS_ROOT_PATH . '/footer.php';
