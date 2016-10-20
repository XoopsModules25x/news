<?php
/**
 * News module
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright      XOOPS Project (http://xoops.org)
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @package        news
 * @since          1.6.7
 * @author         XOOPS Development Team
 **/
$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';

global $xoopsModule;

$moduleDirName  = $GLOBALS['xoopsModule']->getVar('dirname');
$thisModulePath = dirname(__DIR__);

//if functions.php file exist
//require_once __DIR__ . '/../include/functions.php';
require_once $thisModulePath . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $moduleDirName);
xoops_loadLanguage('modinfo', $moduleDirName);
xoops_loadLanguage('main', $moduleDirName);

$pathIcon16      = '../' . $xoopsModule->getInfo('icons16');
$pathIcon32      = '../' . $xoopsModule->getInfo('icons32');
$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

if (file_exists($GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php'))) {
    include_once $GLOBALS['xoops']->path($pathModuleAdmin . '/moduleadmin.php');
} else {
    redirect_header('../../../admin.php', 5, _AM_MODULEADMIN_MISSING, false);
}

$topicsHandler  = xoops_getModuleHandler('news_topics', 'news');
$storiesHandler = xoops_getModuleHandler('news_stories', 'news');

$myts = MyTextSanitizer::getInstance();

if ($xoopsUser) {
    $moduleperm_handler = xoops_getHandler('groupperm');
    if (!$moduleperm_handler->checkRight('module_admin', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

if (!isset($xoopsTpl) || !is_object($xoopsTpl)) {
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $xoopsTpl = new XoopsTpl();
}

$xoopsTpl->assign('pathIcon16', $pathIcon16);
