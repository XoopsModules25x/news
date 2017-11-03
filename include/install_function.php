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
 * @copyright   {@link https://xoops.org/ XOOPS Project}
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Voltan
 * @package     News
 * @param $xoopsModule
 * @return bool
 */

function xoops_module_pre_install_news(XoopsModule $xoopsModule)
{
    // Check if this XOOPS version is supported
    $minSupportedVersion = explode('.', '2.5.0');
    $currentVersion      = explode('.', substr(XOOPS_VERSION, 6));

    if ($currentVersion[0] > $minSupportedVersion[0]) {
        return true;
    } elseif ($currentVersion[0] == $minSupportedVersion[0]) {
        if ($currentVersion[1] > $minSupportedVersion[1]) {
            return true;
        } elseif ($currentVersion[1] == $minSupportedVersion[1]) {
            if ($currentVersion[2] > $minSupportedVersion[2]) {
                return true;
            } elseif ($currentVersion[2] == $minSupportedVersion[2]) {
                return true;
            }
        }
    }

    return false;
}

/**
 * @param XoopsModule $xoopsModule
 *
 * @return bool
 */
function xoops_module_install_news(XoopsModule $xoopsModule)
{
    $module_id     = $xoopsModule->getVar('mid');
    $gpermHandler  = xoops_getHandler('groupperm');
    $configHandler = xoops_getHandler('config');

    /**
     * Default public category permission mask
     */

    // Access right
    $gpermHandler->addRight('news_approve', 1, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('news_submit', 1, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('news_view', 1, XOOPS_GROUP_ADMIN, $module_id);

    $gpermHandler->addRight('news_view', 1, XOOPS_GROUP_USERS, $module_id);
    $gpermHandler->addRight('news_view', 1, XOOPS_GROUP_ANONYMOUS, $module_id);

    $dir = XOOPS_ROOT_PATH . '/uploads/news';
    //    if (!is_dir($dir)) {
    //        mkdir($dir, 0777);
    //    }

    if (!@mkdir($dir) && !is_dir($dir)) {
        throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
    }

    chmod($dir, 0777);

    $dir = XOOPS_ROOT_PATH . '/uploads/news/file';
    if (!@mkdir($dir) && !is_dir($dir)) {
        throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
    }
    chmod($dir, 0777);

    $dir = XOOPS_ROOT_PATH . '/uploads/news/image';
    if (!@mkdir($dir) && !is_dir($dir)) {
        throw new \RuntimeException('The directory ' . $dir . ' could not be created.');
    }
    chmod($dir, 0777);

    // Copy index.html files on uploads folders
    $indexFile = XOOPS_ROOT_PATH . '/modules/news/include/index.html';
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/file/index.html');
    copy($indexFile, XOOPS_ROOT_PATH . '/uploads/news/image/index.html');

    return true;
}
