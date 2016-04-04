<?php
/**
 * News functions
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   {@link http://xoops.org/ XOOPS Project}
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Voltan
 * @package     News
 */

function xoops_module_update_news()
{
    include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    global $xoopsDB;
    $errors = 0;

    //0) Rename all tables

    if (news_TableExists($xoopsDB->prefix('stories_files'))) {
        $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('stories_files') . ' RENAME ' . $xoopsDB->prefix('news_stories_files'));
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
            ++$errors;
        }
    } else {

        // 1) Create, if it does not exists, the stories_files table
        if (!news_TableExists($xoopsDB->prefix('news_stories_files'))) {
            $sql = 'CREATE TABLE ' . $xoopsDB->prefix('news_stories_files') . " (
              fileid int(8) unsigned NOT NULL auto_increment,
              filerealname varchar(255) NOT NULL default '',
              storyid int(8) unsigned NOT NULL default '0',
              date int(10) NOT NULL default '0',
              mimetype varchar(64) NOT NULL default '',
              downloadname varchar(255) NOT NULL default '',
              counter int(8) unsigned NOT NULL default '0',
              PRIMARY KEY  (fileid),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
            if (!$xoopsDB->queryF($sql)) {
                echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED1;
                ++$errors;
            }
        }
    }

    if (news_TableExists($xoopsDB->prefix('stories'))) {
        $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('stories') . ' RENAME ' . $xoopsDB->prefix('news_stories'));
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (news_TableExists($xoopsDB->prefix('topics'))) {
        $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('topics') . ' RENAME ' . $xoopsDB->prefix('news_topics'));
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
            ++$errors;
        }
    }

    if (news_TableExists($xoopsDB->prefix('stories_files'))) {
        $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('stories_files') . ' RENAME ' . $xoopsDB->prefix('news_stories_files'));
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
            ++$errors;
        }
    }

    // 2) Change the topic title's length, in the topics table
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' CHANGE topic_title topic_title VARCHAR( 255 ) NOT NULL;');
    $result = $xoopsDB->queryF($sql);
    if (!$result) {
        echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
        ++$errors;
    }

    // 2.1) Add the new fields to the topic table
    if (!news_FieldExists('menu', $xoopsDB->prefix('news_topics'))) {
        news_AddField("menu TINYINT( 1 ) DEFAULT '0' NOT NULL", $xoopsDB->prefix('news_topics'));
    }
    if (!news_FieldExists('topic_frontpage', $xoopsDB->prefix('news_topics'))) {
        news_AddField("topic_frontpage TINYINT( 1 ) DEFAULT '1' NOT NULL", $xoopsDB->prefix('news_topics'));
    }
    if (!news_FieldExists('topic_rssurl', $xoopsDB->prefix('news_topics'))) {
        news_AddField('topic_rssurl VARCHAR( 255 ) NOT NULL', $xoopsDB->prefix('news_topics'));
    }
    if (!news_FieldExists('topic_description', $xoopsDB->prefix('news_topics'))) {
        news_AddField('topic_description TEXT NOT NULL', $xoopsDB->prefix('news_topics'));
    }
    if (!news_FieldExists('topic_color', $xoopsDB->prefix('news_topics'))) {
        news_AddField("topic_color varchar(6) NOT NULL default '000000'", $xoopsDB->prefix('news_topics'));
    }

    // 3) If it does not exists, create the table stories_votedata
    if (!news_TableExists($xoopsDB->prefix('news_stories_votedata'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('news_stories_votedata') . " (
              ratingid int(11) unsigned NOT NULL auto_increment,
              storyid int(8) unsigned NOT NULL default '0',
              ratinguser int(11) NOT NULL default '0',
              rating tinyint(3) unsigned NOT NULL default '0',
              ratinghostname varchar(60) NOT NULL default '',
              ratingtimestamp int(10) NOT NULL default '0',
              PRIMARY KEY  (ratingid),
              KEY ratinguser (ratinguser),
              KEY ratinghostname (ratinghostname),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br />' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED3;
            ++$errors;
        }
    }

    // 4) Create the four new fields for the votes in the story table
    if (!news_FieldExists('rating', $xoopsDB->prefix('news_stories'))) {
        news_AddField("rating DOUBLE( 6, 4 ) DEFAULT '0.0000' NOT NULL", $xoopsDB->prefix('news_stories'));
    }
    if (!news_FieldExists('votes', $xoopsDB->prefix('news_stories'))) {
        news_AddField("votes INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", $xoopsDB->prefix('news_stories'));
    }
    if (!news_FieldExists('keywords', $xoopsDB->prefix('news_stories'))) {
        news_AddField('keywords VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!news_FieldExists('description', $xoopsDB->prefix('news_stories'))) {
        news_AddField('description VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!news_FieldExists('pictureinfo', $xoopsDB->prefix('news_stories'))) {
        news_AddField('pictureinfo VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!news_FieldExists('subtitle', $xoopsDB->prefix('news_stories'))) {
        news_AddField('subtitle VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }

    // 5) Add some indexes to the topics table
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' ADD INDEX ( `topic_title` );');
    $result = $xoopsDB->queryF($sql);
    $sql    = sprintf('ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' ADD INDEX ( `menu` );');
    $result = $xoopsDB->queryF($sql);

    // 6) Make files and folders
    $dir = XOOPS_ROOT_PATH . '/uploads/news';
    if (!is_dir($dir)) {
        mkdir($dir);
        chmod($dir, 0777);
    } elseif (!is_writable($dir)) {
        chmod($dir, 0777);
    }

    $dir = XOOPS_ROOT_PATH . '/uploads/news/file';
    if (!is_dir($dir)) {
        mkdir($dir);
        chmod($dir, 0777);
    } elseif (!is_writable($dir)) {
        chmod($dir, 0777);
    }

    $dir = XOOPS_ROOT_PATH . '/uploads/news/image';
    if (!is_dir($dir)) {
        mkdir($dir);
        chmod($dir, 0777);
    } elseif (!is_writable($dir)) {
        chmod($dir, 0777);
    }

    // Copy index.html files on uploads folders
    $indexFile = XOOPS_ROOT_PATH . '/modules/news/include/index.html';
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/file/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/image/index.html');

    return true;
}
