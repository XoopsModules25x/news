<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2005-2006 Herve Thouzard                     //
//                     <http://www.herve-thouzard.com/>                      //
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

/*
 * Created on 28 oct. 2006
 *
 * This file is responsible for creating micro summaries for Firefox 2 web navigator
 * For more information, see this page : http://wiki.mozilla.org/Microsummaries
 *
 * @package News
 * @author Herve Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Herve Thouzard
 *
 * NOTE : If you use this code, please make credit.
 *
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
if (!news_getmoduleoption('firefox_microsummaries')) {
    exit();
}
$story      = new NewsStory();
$restricted = news_getmoduleoption('restrictindex');
$sarray     = array();
// Get the last news from all topics according to the module's restrictions
$sarray = NewsStory::getAllPublished(1, 0, $restricted, 0);
if (count($sarray) > 0) {
    $laststory = null;
    $laststory = $sarray[0];
    if (is_object($laststory)) {
        header('Content-Type:text;');
        echo $laststory->title() . ' - ' . $xoopsConfig['sitename'];
    }
}
