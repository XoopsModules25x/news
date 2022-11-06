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

use XoopsModules\News;
use XoopsModules\News\Utility;

require \dirname(__DIR__, 3) . '/include/cp_header.php';
xoops_cp_header();

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $errors = 0;
    // 1) Create, if it does not exists, the stories_files table
    if (!Utility::existTable($xoopsDB->prefix('news_stories_files'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('news_stories_files') . " (
              fileid INT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
              filerealname VARCHAR(255) NOT NULL DEFAULT '',
              storyid INT(8) UNSIGNED NOT NULL DEFAULT '0',
              date INT(10) NOT NULL DEFAULT '0',
              mimetype VARCHAR(64) NOT NULL DEFAULT '',
              downloadname VARCHAR(255) NOT NULL DEFAULT '',
              counter INT(8) UNSIGNED NOT NULL DEFAULT '0',
              PRIMARY KEY  (fileid),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED1;
            ++$errors;
        }
    }

    // 2) Change the topic title's length, in the topics table
    $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' CHANGE topic_title topic_title VARCHAR( 255 ) NOT NULL;';
    $result = $xoopsDB->queryF($sql);
    if (!$result) {
        echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
        ++$errors;
    }

    // 2.1) Add the new fields to the topic table
    if (!Utility::existField('menu', $xoopsDB->prefix('news_topics'))) {
        Utility::addField("menu TINYINT( 1 ) DEFAULT '0' NOT NULL", $xoopsDB->prefix('news_topics'));
    }
    if (!Utility::existField('topic_frontpage', $xoopsDB->prefix('news_topics'))) {
        Utility::addField("topic_frontpage TINYINT( 1 ) DEFAULT '1' NOT NULL", $xoopsDB->prefix('news_topics'));
    }
    if (!Utility::existField('topic_rssurl', $xoopsDB->prefix('news_topics'))) {
        Utility::addField('topic_rssurl VARCHAR( 255 ) NOT NULL', $xoopsDB->prefix('news_topics'));
    }
    if (!Utility::existField('topic_description', $xoopsDB->prefix('news_topics'))) {
        Utility::addField('topic_description TEXT NOT NULL', $xoopsDB->prefix('news_topics'));
    }
    if (!Utility::existField('topic_color', $xoopsDB->prefix('news_topics'))) {
        Utility::addField("topic_color varchar(6) NOT NULL default '000000'", $xoopsDB->prefix('news_topics'));
    }

    // 3) If it does not exists, create the table stories_votedata
    if (!Utility::existTable($xoopsDB->prefix('news_stories_votedata'))) {
        $sql = 'CREATE TABLE ' . $xoopsDB->prefix('news_stories_votedata') . " (
              ratingid INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
              storyid INT(8) UNSIGNED NOT NULL DEFAULT '0',
              ratinguser INT(11) NOT NULL DEFAULT '0',
              rating TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
              ratinghostname VARCHAR(60) NOT NULL DEFAULT '',
              ratingtimestamp INT(10) NOT NULL DEFAULT '0',
              PRIMARY KEY  (ratingid),
              KEY ratinguser (ratinguser),
              KEY ratinghostname (ratinghostname),
              KEY storyid (storyid)
            ) ENGINE=MyISAM;";
        if (!$xoopsDB->queryF($sql)) {
            echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED3;
            ++$errors;
        }
    }

    // 4) Create the four new fields for the votes in the story table
    if (!Utility::existField('rating', $xoopsDB->prefix('news_stories'))) {
        Utility::addField("rating DOUBLE( 6, 4 ) DEFAULT '0.0000' NOT NULL", $xoopsDB->prefix('news_stories'));
    }
    if (!Utility::existField('votes', $xoopsDB->prefix('news_stories'))) {
        Utility::addField("votes INT( 11 ) UNSIGNED DEFAULT '0' NOT NULL", $xoopsDB->prefix('news_stories'));
    }
    if (!Utility::existField('keywords', $xoopsDB->prefix('news_stories'))) {
        Utility::addField('keywords VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!Utility::existField('description', $xoopsDB->prefix('news_stories'))) {
        Utility::addField('description VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!Utility::existField('pictureinfo', $xoopsDB->prefix('news_stories'))) {
        Utility::addField('pictureinfo VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }
    if (!Utility::existField('subtitle', $xoopsDB->prefix('news_stories'))) {
        Utility::addField('subtitle VARCHAR(255) NOT NULL', $xoopsDB->prefix('news_stories'));
    }

    // 5) Add some indexes to the topics table
    $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' ADD INDEX ( `topic_title` );';
    $result = $xoopsDB->queryF($sql);
    $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('news_topics') . ' ADD INDEX ( `menu` );';
    $result = $xoopsDB->queryF($sql);

    // At the end, if there was errors, show them or redirect user to the module's upgrade page
    if ($errors) {
        echo '<H1>' . _AM_NEWS_UPGRADEFAILED . '</H1>';
        echo '<br>' . _AM_NEWS_UPGRADEFAILED0;
    } else {
        echo _AM_NEWS_UPGRADECOMPLETE . " - <a href='" . XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=news'>" . _AM_NEWS_UPDATEMODULE . '</a>';
    }
} else {
    printf("<h2>%s</h2>\n", _AM_NEWS_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
