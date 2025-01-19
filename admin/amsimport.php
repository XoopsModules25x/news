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
 * AMS Import
 *
 * This script will import topics, articles, files, links, ratings, comments and notifications from AMS 2.41
 *
 * @author    Hervé Thouzard (https://www.herve-thouzard.com)
 * @copyright 2005, 2006 - Hervé Thouzard
 */

use XoopsModules\Ams\Helper as AmsHelper;
use XoopsModules\News\{
    Files,
    NewsStory,
    NewsTopic
};

require \dirname(__DIR__, 3) . '/include/cp_header.php';
xoops_cp_header();

//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
//require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    if (isset($_POST['go'])) {
        // Launch the import
        /** @var \XoopsModules\Ams\Helper $amsHelper */
        $amsHelper = AmsHelper::getInstance();
        $amsHelper->loadLanguage('admin');
        $amsHelper->loadLanguage('main');

        /** @var \XoopsMySQLDatabase $db */
        $db = \XoopsDatabaseFactory::getDatabaseConnection();
        // User's choices
        $use_forum    = (isset($_POST['useforum']) && 1 == $_POST['useforum']) ? 1 : 0;
        $use_extlinks = (isset($_POST['useextlinks']) && 1 == $_POST['useextlinks']) ? 1 : 0;
        // Retrieve News module's ID
        /** @var \XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $newsModule    = $moduleHandler->getByDirname('news');
        $news_mid      = $newsModule->getVar('mid');
        // Retrieve AMS module's ID
        $AmsModule = $moduleHandler->getByDirname('AMS');
        $ams_mid   = $AmsModule->getVar('mid');

        // Retrieve AMS tables names
        $ams_topics   = $xoopsDB->prefix('ams_topics');
        $ams_articles = $xoopsDB->prefix('ams_article');
        $ams_text     = $xoopsDB->prefix('ams_text');
        $ams_files    = $xoopsDB->prefix('ams_files');
        $ams_links    = $xoopsDB->prefix('ams_link');
        $ams_rating   = $xoopsDB->prefix('ams_rating');
        // Retrieve News tables names
        $news_stories_votedata = $xoopsDB->prefix('news_stories_votedata');
        // Misc
        $commentHandler = xoops_getHandler('comment');
        /** @var \XoopsNotificationHandler $notificationHandler */
        $notificationHandler = xoops_getHandler('notification');
        $ams_news_topics     = []; // Key => AMS Id,  Value => News ID

        // The import by itself
        // Read topics by their order
        $mytree     = new \XoopsTree($ams_topics, 'topic_id', 'topic_pid');
        $ams_topics = $mytree->getChildTreeArray(0, 'weight');
        foreach ($ams_topics as $one_amstopic) {
            // First we create the topic
            $topicpid = 0;
            if (0 != $one_amstopic['topic_pid']) { // Search for its the parent
                if (array_key_exists($one_amstopic['topic_pid'], $ams_news_topics)) {
                    $topicpid = $ams_news_topics[$one_amstopic['topic_pid']];
                }
            }
            $news_topic = new NewsTopic();
            $news_topic->setTopicPid($topicpid);
            $news_topic->setTopicTitle($one_amstopic['topic_title']);
            $news_topic->setTopicImgurl($one_amstopic['topic_imgurl']);
            $news_topic->setMenu(0);
            $news_topic->setTopicFrontpage(1);
            $news_topic->setTopicRssUrl('');
            $news_topic->setTopicDescription('');
            $news_topic->setTopic_color('000000');
            $news_topic->store();
            echo '<br>- The following topic was imported : ' . $news_topic->topic_title();
            $ams_topicid                   = $one_amstopic['topic_id'];
            $news_topicid                  = $news_topic->topic_id();
            $ams_news_topics[$ams_topicid] = $news_topicid;

            // Then we insert all its articles
            $sql = 'SELECT * FROM ' . $ams_articles . ' WHERE topicid=' . $ams_topicid . ' ORDER BY created';
            $result = $db->query($sql);

            if (!$db->isResultSet($result)) {
                \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
            }

            while (false !== ($article = $db->fetchArray($result))) {
                $ams_newsid = $article['storyid'];

                // We search for the last version
                $sql = 'SELECT * FROM ' . $ams_text . ' WHERE storyid=' . $ams_newsid . ' AND current=1';
                $result2          = $db->query($sql);
                if (!$db->isResultSet($result2)) {
                    \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
                }
                $text_lastversion = $db->fetchArray($result2);

                // We search for the number of votes
                $sql = 'SELECT count(*) AS cpt FROM ' . $ams_rating . ' WHERE storyid=' . $ams_newsid;
                $result3 = $db->query($sql);
                if (!$db->isResultSet($result3)) {
                    \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
                }
                $votes   = $db->fetchArray($result3);

                // The links
                $links = '';
                if ($use_extlinks) {
                    $sql = 'SELECT * FROM ' . $ams_links . ' WHERE storyid=' . $ams_newsid . ' ORDER BY linkid';
                    $result7 = $db->query($sql);
                    if (!$db->isResultSet($result7)) {
                        \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
                    }
                    while (false !== ($link = $db->fetchArray($result7))) {
                        if ('' == trim($links)) {
                            $links = "\n\n" . _AMS_NW_RELATEDARTICLES . "\n\n";
                        }
                        $links .= _AMS_NW_EXTERNALLINK . ' [url=' . $link['link_link'] . ']' . $link['link_title'] . '[/url]' . "\n";
                    }
                }

                // The forum
                $forum = '';
                if ($use_forum && 0 != $one_amstopic['forum_id']) {
                    $forum = "\n\n" . '[url=' . XOOPS_URL . '/modules/newbb/viewforum.php?forum=' . $one_amstopic['forum_id'] . ']' . _AMS_AM_LINKEDFORUM . '[/url]' . "\n";
                }

                // We create the story
                $news = new NewsStory();
                $news->setUid($text_lastversion['uid']);
                $news->setTitle($article['title']);
                $news->created = $article['created'];
                $news->setPublished($article['published']);
                $news->setExpired($article['expired']);
                $news->setHostname($article['hostname']);
                $news->setNohtml($article['nohtml']);
                $news->setNosmiley($article['nosmiley']);
                $news->setHometext($text_lastversion['hometext']);
                $news->setBodytext($text_lastversion['bodytext'] . $links . $forum);
                $news->setKeywords('');
                $news->setDescription('');
                $news->counter = $article['counter'];
                $news->setTopicId($news_topicid);
                $news->setIhome($article['ihome']);
                $news->setNotifyPub($article['notifypub']);
                $news->story_type = $article['story_type'];
                $news->setTopicdisplay($article['topicdisplay']);
                $news->setTopicalign($article['topicalign']);
                $news->setComments($article['comments']);
                $news->rating   = $article['rating'];
                $news->votes    = $votes['cpt'];
                $approved       = $article['published'] > 0;
                $news->approved = $approved;
                $news->store($approved);
                echo '<br>&nbsp;&nbsp;This story was imported : ' . $news->title();
                $news_newsid = $news->storyid(); // ********************

                // The files
                $sql = 'SELECT * FROM ' . $ams_files . ' WHERE storyid=' . $ams_newsid;
                $result4 = $db->query($sql);
                if (!$db->isResultSet($result4)) {
                    \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
                }
                while (false !== ($file = $db->fetchArray($result4))) {
                    $sfile = new Files();
                    $sfile->setFileRealName($file['filerealname']);
                    $sfile->setStoryid($news_newsid);
                    $sfile->date = $file['date'];
                    $sfile->setMimetype($file['mimetype']);
                    $sfile->setDownloadname($file['downloadname']);
                    $sfile->counter = $file['counter'];
                    $sfile->store();
                    echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;This file was imported : ' . $sfile->getDownloadname();
                    $news_fileid = $sfile->fileid;
                }

                // The ratings
                $sql = 'SELECT * FROM ' . $ams_rating . ' WHERE storyid=' . $ams_newsid;
                $result5 = $db->query($sql);
                if (!$db->isResultSet($result5)) {
                    \trigger_error("Query Failed! SQL: $sql Error: " . $db->error(), \E_USER_ERROR);
                }
                while (false !== ($ratings = $db->fetchArray($result5))) {
                    $sql = ' INSERT INTO ' . $news_stories_votedata . ' (storyid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (' . $news_newsid . ',' . $ratings['ratinguser'] . ',' . $ratings['rating'] . ',' . $ratings['ratinghostname'] . ',' . $ratings['ratingtimestamp'] . ')';
                    $result6 = $db->queryF($sql);
                }

                // The comments
                $comments = $commentHandler->getByItemId($ams_mid, $ams_newsid, 'ASC');
                if ($comments && \is_array($comments)) {
                    foreach ($comments as $onecomment) {
                        $onecomment->setNew();
                        $onecomment->setVar('com_modid', $news_mid);
                        $onecomment->setVar('com_itemid', $news_newsid);
                        $commentHandler->insert($onecomment);
                    }
                }
                unset($comments);

                // The notifications of this news
                //$notifications =& $notificationHandler->getByItemId($ams_mid, $ams_newsid, 'ASC');
                $criteria = new \CriteriaCompo(new \Criteria('not_modid', $ams_mid));
                $criteria->add(new \Criteria('not_itemid', $ams_newsid));
                $criteria->setOrder('ASC');
                $notifications = $notificationHandler->getObjects($criteria);
                if ($notifications && \is_array($notifications)) {
                    foreach ($notifications as $onenotification) {
                        $onenotification->setNew();
                        $onenotification->setVar('not_modid', $news_mid);
                        $onenotification->setVar('not_itemid', $news_newsid);
                        $notificationHandler->insert($onenotification);
                    }
                }
                unset($notifications);
            }
        }
        // Finally, import all the globals notifications
        $criteria = new \CriteriaCompo(new \Criteria('not_modid', $ams_mid));
        $criteria->add(new \Criteria('not_category', 'global'));
        $criteria->setOrder('ASC');
        $notifications = $notificationHandler->getObjects($criteria);
        if ($notifications && \is_array($notifications)) {
            foreach ($notifications as $onenotification) {
                $onenotification->setNew();
                $onenotification->setVar('not_modid', $news_mid);
                $onenotification->setVar('not_itemid', $news_newsid);
                $notificationHandler->insert($onenotification);
            }
        }
        unset($notifications);
        echo "<p><a href='" . XOOPS_URL . "/modules/news/admin/groupperms.php'>The import is finished, don't forget to verify and set the topics permissions !</a></p>";
    } else {
        echo '<h1>Welcome to the AMS 2.41 import script</h1>';
        echo '<br><br>Select the import options you wan to use :';
        echo "<form method='post' action='amsimport.php'>";
        echo "<br><input type='checkbox' name='useforum' value='1'> Import forums links inside news (at the bottom of the news)";
        echo "<br><input type='checkbox' name='useextlinks' value='1'> Import external links inside news (at the bottom of the news)";
        echo "<br><br><input type='submit' name='go' value='Import'>";
        echo '</form>';
        echo "<br><br>If you check the two last options then the forum's link and all the external links will be added at the end of the body text.";
    }
} else {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}
xoops_cp_footer();
