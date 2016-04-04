<?php
// 
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
//  ------------------------------------------------------------------------ //
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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

$path = dirname(dirname(dirname(__DIR__)));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(__DIR__));
$module_handler  = xoops_getHandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu   = array();
$adminmenu[] = array(
    'title' => _MI_NEWS_HOME,
    'link'  => 'admin/index.php',
    'icon'  => $pathIcon32 . '/home.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_ADMENU2,
    'link'  => 'admin/index.php?op=topicsmanager',
    'icon'  => $pathIcon32 . '/category.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_ADMENU3,
    'link'  => 'admin/index.php?op=newarticle',
    'icon'  => $pathIcon32 . '/content.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_GROUPPERMS,
    'link'  => 'admin/groupperms.php',
    'icon'  => $pathIcon32 . '/permissions.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_PRUNENEWS,
    'link'  => 'admin/index.php?op=prune',
    'icon'  => $pathIcon32 . '/prune.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_EXPORT,
    'link'  => 'admin/index.php?op=export',
    'icon'  => $pathIcon32 . '/export.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_NEWSLETTER,
    'link'  => 'admin/index.php?op=configurenewsletter',
    'icon'  => $pathIcon32 . '/newsletter.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_STATS,
    'link'  => 'admin/index.php?op=stats',
    'icon'  => $pathIcon32 . '/stats.png');

if (isset($xoopsModule) && $xoopsModule->getVar('version') != 167) {
    $adminmenu[] = array(
        'title' => _MI_NEWS_UPGRADE,
        'link'  => 'admin/upgrade.php',
        'icon'  => $pathIcon32 . '/update.png');
}

$adminmenu[] = array(
    'title' => _MI_NEWS_METAGEN,
    'link'  => 'admin/index.php?op=metagen',
    'icon'  => $pathIcon32 . '/metagen.png');

$adminmenu[] = array(
    'title' => _MI_NEWS_ABOUT,
    'link'  => 'admin/about.php',
    'icon'  => $pathIcon32 . '/about.png');
