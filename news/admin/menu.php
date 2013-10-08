<?php
// $Id: menu.php 12097 2013-09-26 15:56:34Z beckmi $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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

defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i = 1;
$adminmenu[$i]["title"] = _MI_NEWS_HOME;
$adminmenu[$i]["link"]  = "admin/index.php";
$adminmenu[$i]["icon"] = $pathIcon32.'/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_ADMENU2;
$adminmenu[$i]['link'] = 'admin/index.php?op=topicsmanager';
$adminmenu[$i]['icon'] = $pathIcon32.'/category.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_ADMENU3;
$adminmenu[$i]['link'] = 'admin/index.php?op=newarticle';
$adminmenu[$i]['icon'] = $pathIcon32.'/content.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_GROUPPERMS;
$adminmenu[$i]['link'] = 'admin/groupperms.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/permissions.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_PRUNENEWS;
$adminmenu[$i]['link'] = 'admin/index.php?op=prune';
$adminmenu[$i]['icon'] = $pathIcon32.'/prune.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_EXPORT;
$adminmenu[$i]['link'] = 'admin/index.php?op=export';
$adminmenu[$i]['icon'] = $pathIcon32.'/export.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_NEWSLETTER;
$adminmenu[$i]['link'] = 'admin/index.php?op=configurenewsletter';
$adminmenu[$i]['icon'] = $pathIcon32.'/newsletter.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_STATS;
$adminmenu[$i]['link'] = 'admin/index.php?op=stats';
$adminmenu[$i]['icon'] = $pathIcon32.'/stats.png';

if (isset($xoopsModule) && $xoopsModule->getVar('version') != 167) {
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_UPGRADE;
$adminmenu[$i]['link'] = "admin/upgrade.php";
$adminmenu[$i]['icon'] = $pathIcon32.'/update.png';
}
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_METAGEN;
$adminmenu[$i]['link'] = "admin/index.php?op=metagen";
$adminmenu[$i]['icon'] = $pathIcon32.'/metagen.png';
$i++;
$adminmenu[$i]['title'] = _MI_NEWS_ABOUT;
$adminmenu[$i]['link'] = 'admin/about.php';
$adminmenu[$i]['icon'] = $pathIcon32.'/about.png';
