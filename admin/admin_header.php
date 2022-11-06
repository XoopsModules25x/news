<?php declare(strict_types=1);

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
 * @copyright      XOOPS Project (https://xoops.org)
 * @license        https://www.gnu.org/licenses/gpl-2.0.html GNU Public License
 * @since          1.6.7
 * @author         XOOPS Development Team
 **/

use Xmf\Module\Admin;
use XoopsModules\News;
use XoopsModules\News\Helper;

require \dirname(__DIR__, 3) . '/include/cp_header.php';
require \dirname(__DIR__, 3) . '/class/xoopsformloader.php';
// require_once  \dirname(__DIR__) . '/class/Utility.php';
require_once \dirname(__DIR__) . '/include/common.php';

require_once \dirname(__DIR__) . '/preloads/autoloader.php';

$moduleDirName = \basename(\dirname(__DIR__));
/** @var \XoopsModules\News\Helper $helper */
$helper = Helper::getInstance();

/** @var Xmf\Module\Admin $adminObject */
$adminObject = Admin::getInstance();

$pathIcon16 = Admin::iconUrl('', '16');
$pathIcon32 = Admin::iconUrl('', '32');

$pathModIcon32 = $helper->getModule()->getInfo('modicons32');

// Load language files
$helper->loadLanguage('admin');
$helper->loadLanguage('modinfo');
$helper->loadLanguage('main');
$helper->loadLanguage('common');

$myts = \MyTextSanitizer::getInstance();

if (!isset($GLOBALS['xoopsTpl']) || !($GLOBALS['xoopsTpl'] instanceof \XoopsTpl)) {
    require_once $GLOBALS['xoops']->path('class/template.php');
    $xoopsTpl = new \XoopsTpl();
}

$topicsHandler  = $helper->getHandler('NewsTopics');
$storiesHandler = $helper->getHandler('NewsStories');

if ($xoopsUser) {
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    if (!$grouppermHandler->checkRight('module_admin', $xoopsModule->getVar('mid'), $xoopsUser->getGroups())) {
        redirect_header(XOOPS_URL, 1, _NOPERM);
    }
} else {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

$xoopsTpl->assign('pathIcon16', $pathIcon16);
