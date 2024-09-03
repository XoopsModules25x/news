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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team
 */

use XoopsModules\News\{
    Common\Configurator,
    Helper,
    Utility
};


if ((!defined('XOOPS_ROOT_PATH')) || !($GLOBALS['xoopsUser'] instanceof \XoopsUser)
    || !$GLOBALS['xoopsUser']->isAdmin()) {
    exit('Restricted access' . PHP_EOL);
}

/**
 * Prepares system prior to attempting to install module
 * @param \XoopsModule $module {@link \XoopsModule}
 *
 * @return bool true if ready to install, false if not
 */
function xoops_module_pre_update_news(\XoopsModule $module): bool
{
    $moduleDirName = \basename(\dirname(__DIR__));
    /** @var Helper $helper */
    /** @var Utility $utility */
    $helper  = Helper::getInstance();
    $utility = new Utility();

    $xoopsSuccess = $utility::checkVerXoops($module);
    $phpSuccess   = $utility::checkVerPhp($module);

    return $xoopsSuccess && $phpSuccess;
}

/**
 * Performs tasks required during update of the module
 * @param \XoopsModule $module {@link \XoopsModule}
 * @param null         $previousVersion
 *
 * @return bool true if update successful, false if not
 */
function xoops_module_update_news(\XoopsModule $module, $previousVersion = null): bool
{
    $moduleDirName      = \basename(\dirname(__DIR__));
    $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

    /** @var Helper $helper */
    /** @var Utility $utility */
    /** @var Configurator $configurator */
    $helper       = Helper::getInstance();
    $utility      = new Utility();
    $configurator = new Configurator();

    if ($previousVersion < 174) {

        global $xoopsDB;
        $errors = 0;

        //0) Rename all tables

        if (Utility::existTable($xoopsDB->prefix('stories_files'))) {
            $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('stories_files') . ' RENAME ' . $xoopsDB->prefix('news_stories_files');
            $result = $xoopsDB->queryF($sql);
            if (!$result) {
                echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
                ++$errors;
            }
        } elseif (!Utility::existTable($xoopsDB->prefix('news_stories_files'))) {
            // 1) Create, if it does not exists, the stories_files table
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

        if (Utility::existTable($xoopsDB->prefix('stories'))) {
            $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('stories') . ' RENAME ' . $xoopsDB->prefix('news_stories');
            $result = $xoopsDB->queryF($sql);
            if (!$result) {
                echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
                ++$errors;
            }
        }

        if (Utility::existTable($xoopsDB->prefix('topics'))) {
            $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('topics') . ' RENAME ' . $xoopsDB->prefix('news_topics');
            $result = $xoopsDB->queryF($sql);
            if (!$result) {
                echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
                ++$errors;
            }
        }

        if (Utility::existTable($xoopsDB->prefix('stories_files'))) {
            $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('stories_files') . ' RENAME ' . $xoopsDB->prefix('news_stories_files');
            $result = $xoopsDB->queryF($sql);
            if (!$result) {
                echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
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

        // 2.0a) change column size for IP address from varchar(16) to varchar(45) for IPv6
        $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('news_stories') . ' MODIFY hostname VARCHAR( 45 ) NOT NULL DEFAULT \'\';';
        $result = $xoopsDB->queryF($sql);
        if (!$result) {
            echo '<br>' . _AM_NEWS_UPGRADEFAILED . ' ' . _AM_NEWS_UPGRADEFAILED2;
            ++$errors;
        }

        // 2.0b)  change column size for Picture from varchar(50) to varchar(255) for SEO
        $sql    = 'ALTER TABLE ' . $xoopsDB->prefix('news_stories') . ' MODIFY picture VARCHAR( 255 ) NOT NULL DEFAULT \'\';';
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

        // 6) Make files and folders
        $dir = XOOPS_ROOT_PATH . '/uploads/news';
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
        }
        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }

        $dir = XOOPS_ROOT_PATH . '/uploads/news/file';
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
        }
        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }

        $dir = XOOPS_ROOT_PATH . '/uploads/news/image';
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
        }
        if (!is_writable($dir)) {
            chmod($dir, 0777);
        }

        // Copy index.html files on uploads folders
        $indexFile = XOOPS_ROOT_PATH . '/modules/news/include/index.php';
        copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/index.php');
        copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/file/index.php');
        copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/image/index.php');
    }

//        $module = $helper->getModule();

//    if (true === $module->versionCompare($previousVersion, $module->getInfo('version'))) {
        //delete old HTML templates
        if (count($configurator->templateFolders) > 0) {
            foreach ($configurator->templateFolders as $folder) {
                $templateFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $folder);
                if (is_dir($templateFolder)) {
                    $templateList = array_diff(scandir($templateFolder, SCANDIR_SORT_NONE), ['..', '.']);
                    foreach ($templateList as $k => $v) {
                        $fileInfo = new \SplFileInfo($templateFolder . $v);
                        if ('html' === $fileInfo->getExtension() && 'index.html' !== $fileInfo->getFilename()) {
                            if (\is_file($templateFolder . $v)) {
                                unlink($templateFolder . $v);
                            }
                        }
                    }
                }
            }
        }

        //  ---  DELETE OLD FILES ---------------
        if (count($configurator->oldFiles) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFiles) as $i) {
                $tempFile = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFiles[$i]);
                if (\is_file($tempFile)) {
                    unlink($tempFile);
                }
            }
        }

        //  ---  DELETE OLD FOLDERS ---------------
        xoops_load('XoopsFile');
        if (count($configurator->oldFolders) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->oldFolders) as $i) {
                $tempFolder = $GLOBALS['xoops']->path('modules/' . $moduleDirName . $configurator->oldFolders[$i]);
                /** @var \XoopsObjectHandler $folderHandler */
                $folderHandler = \XoopsFile::getHandler('folder', $tempFolder);
                $folderHandler->delete($tempFolder);
            }
        }

        //  ---  CREATE FOLDERS ---------------
        if (count($configurator->uploadFolders) > 0) {
            //    foreach (array_keys($GLOBALS['uploadFolders']) as $i) {
            foreach (array_keys($configurator->uploadFolders) as $i) {
                $utility::createFolder($configurator->uploadFolders[$i]);
            }
        }

        //  ---  COPY blank.png FILES ---------------
        if (count($configurator->copyBlankFiles) > 0) {
            $file = \dirname(__DIR__) . '/assets/images/blank.png';
            foreach (array_keys($configurator->copyBlankFiles) as $i) {
                $dest = $configurator->copyBlankFiles[$i] . '/blank.png';
                $utility::copyFile($file, $dest);
            }
        }

        //delete .html entries from the tpl table
//        $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . '\' AND `tpl_file` LIKE \'%.html%\'';
//        $GLOBALS['xoopsDB']->queryF($sql);
    //delete old .html entries from the tpl table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    //delete old .html entries from the newblocks table
    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('newblocks') . " WHERE `mid` = '" . $module->getVar('mid') . "' AND `template` LIKE '%.html%'";
    $GLOBALS['xoopsDB']->queryF($sql);

    //delete old .tpl entries from the tpl table
//    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplfile') . " WHERE `tpl_module` = '" . $module->getVar('dirname', 'n') . "' AND `tpl_file` LIKE '%.tpl%'";
//    $GLOBALS['xoopsDB']->queryF($sql);

    //delete .tpl entries from the tpl_source table
//    $sql = 'DELETE FROM ' . $GLOBALS['xoopsDB']->prefix('tplsource') . " WHERE `tpl_source` LIKE '%' . $module->getVar('dirname', 'n') . '%'";
//    $GLOBALS['xoopsDB']->queryF($sql);

        /** @var \XoopsGroupPermHandler $grouppermHandler */
        $grouppermHandler = xoops_getHandler('groupperm');

        return $grouppermHandler->deleteByModule($module->getVar('mid'), 'item_read');
//    }

    return true;
}
