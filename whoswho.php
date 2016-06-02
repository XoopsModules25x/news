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
 * This page will display a list of the authors of the site
 *
 * @package News
 * @author Herve Thouzard
 * @copyright (c) Herve Thouzard (http://www.herve-thouzard.com)
 */
include dirname(dirname(__DIR__)) . '/mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';

if (!news_getmoduleoption('newsbythisauthor')) {
    redirect_header('index.php', 2, _ERRORS);
}

$xoopsOption['template_main'] = 'news_whos_who.tpl';
include_once XOOPS_ROOT_PATH . '/header.php';

$option  = news_getmoduleoption('displayname');
$article = new NewsStory();
$uid_ids = array();
$uid_ids = $article->getWhosWho(news_getmoduleoption('restrictindex'));
if (count($uid_ids) > 0) {
    $lst_uid        = implode(',', $uid_ids);
    $member_handler = xoops_getHandler('member');
    $critere        = new Criteria('uid', '(' . $lst_uid . ')', 'IN');
    $tbl_users      = $member_handler->getUsers($critere);
    foreach ($tbl_users as $one_user) {
        $uname = '';
        switch ($option) {
            case 1: // Username
                $uname = $one_user->getVar('uname');
                break;

            case 2: // Display full name (if it is not empty)
                if (xoops_trim($one_user->getVar('name')) !== '') {
                    $uname = $one_user->getVar('name');
                } else {
                    $uname = $one_user->getVar('uname');
                }
                break;
        }
        $xoopsTpl->append('whoswho', array('uid' => $one_user->getVar('uid'), 'name' => $uname, 'user_avatarurl' => XOOPS_URL . '/uploads/' . $one_user->getVar('user_avatar')));
    }
}

$xoopsTpl->assign('advertisement', news_getmoduleoption('advertisement'));

/**
 * Manage all the meta datas
 */
news_CreateMetaDatas($article);

$xoopsTpl->assign('xoops_pagetitle', _AM_NEWS_WHOS_WHO);
$myts             = MyTextSanitizer::getInstance();
$meta_description = _AM_NEWS_WHOS_WHO . ' - ' . $xoopsModule->name('s');
if (isset($xoTheme) && is_object($xoTheme)) {
    $xoTheme->addMeta('meta', 'description', $meta_description);
} else { // Compatibility for old Xoops versions
    $xoopsTpl->assign('xoops_meta_description', $meta_description);
}

include_once XOOPS_ROOT_PATH . '/footer.php';
