<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}

include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';

/**
 * Display archives
 * @param array $options :
 * 		0 = sort order (0=older first, 1=newer first)
 * 		1 = Starting date, year
 * 		2 = Starting date, month
 * 		3 = Ending date, year
 * 		4 = Ending date, month
 * 		5 = until today ?
 */
function b_news_archives_show($options)
{
    global $xoopsDB, $xoopsConfig;
    include_once XOOPS_ROOT_PATH.'/modules/news/class/class.newsstory.php';
    include_once XOOPS_ROOT_PATH.'/modules/news/include/functions.php';
    include_once XOOPS_ROOT_PATH.'/language/'.$xoopsConfig['language'].'/calendar.php';
    if (file_exists(XOOPS_ROOT_PATH.'/modules/news/language/'.$xoopsConfig['language'].'/main.php')) {
        include_once XOOPS_ROOT_PATH.'/modules/news/language/'.$xoopsConfig['language'].'/main.php';
    } else {
        include_once XOOPS_ROOT_PATH.'/modules/news/language/english/main.php';
    }

    $months_arr = array(1 => _CAL_JANUARY, 2 => _CAL_FEBRUARY, 3 => _CAL_MARCH, 4 => _CAL_APRIL, 5 => _CAL_MAY, 6 => _CAL_JUNE, 7 => _CAL_JULY, 8 => _CAL_AUGUST, 9 => _CAL_SEPTEMBER, 10 => _CAL_OCTOBER, 11 => _CAL_NOVEMBER, 12 => _CAL_DECEMBER);
    $block = array();
    $sort_order = $options[0] == 0 ? 'ASC' : 'DESC';
    $starting_date = mktime(0,0,0,intval($options[2]), 1, intval($options[1]));
    if (intval($options[5])!=1) {
        $ending_date = mktime(23,59,59,intval($options[4]), 28, intval($options[3]));
    } else {
        $ending_date = time();
    }
    $sql = "SELECT distinct(FROM_UNIXTIME(published,'%Y-%m')) as published FROM ".$xoopsDB->prefix('mod_news_stories').' WHERE published>='.$starting_date .' AND published<='.$ending_date.' ORDER BY published '.$sort_order;
    $result = $xoopsDB->query($sql);
    if (!$result) {
        return '';
    }
    while ($myrow = $xoopsDB->fetchArray($result)) {
        $year = intval(substr($myrow['published'],0,4));
        $month = intval(substr($myrow['published'],5,2));
        $formated_month = $months_arr[$month];
        $block['archives'][] = array('month' => $month, 'year' => $year, 'formated_month' => $formated_month);
    }

    return $block;
}

function b_news_archives_edit($options)
{
    global $xoopsDB;
    $syear = $smonth = $eyear = $emonth = $older = $recent = 0;
    $selsyear = $selsmonth = $seleyear = $selemonth = 0;
    $form = '';

    $selsyear = $options[1];
    $selsmonth = $options[2];
    $seleyear = $options[3];
    $selemonth = $options[4];

    $tmpstory = new NewsStory;
    $tmpstory->GetOlderRecentNews($older, $recent);    // We are searching for the module's older and more recent article's date

    // Min and max value for the two dates selectors
    // We are going to use the older news for the starting date
    $syear = date('Y', $older);
    $smonth = date('n', $older);
    $eyear = date('Y', $recent);
    $emonth = date('n', $recent);
    // Verify parameters
    if ($selsyear == 0 &&  $selsmonth == 0) {
        $selsyear = $syear;
        $selsmonth = $smonth;
    }
    if ($seleyear == 0 && $selemonth == 0) {
        $seleyear = $eyear;
        $selemonth = $emonth;
    }

    // Sort order *************************************************************
    // (0=older first, 1=newer first)
    $form .= '<b>'._MB_NEWS_ORDER."</b>&nbsp;<select name='options[]'>";
    $form .= "<option value='0'";
    if ($options[0] == 0) {
        $form .= " selected='selected'";
    }
    $form .= '>'._MB_NEWS_OLDER_FIRST."</option>\n";
    $form .= "<option value='1'";
    if ($options[0] == 1) {
        $form .= " selected='selected'";
    }
    $form .= '>'._MB_NEWS_RECENT_FIRST.'</option>';
    $form .= "</select>\n";

    // Starting and ending dates **********************************************
    $form .= '<br /><br /><b>'._MB_NEWS_STARTING_DATE.'</b><br />';
    $form .= _MB_NEWS_CAL_YEAR."&nbsp;<select name='options[]'>";
    for ($i=$syear; $i<=$eyear; $i++) {
        $selected = ($i == $selsyear) ? "selected='selected'" : '';
        $form .= "<option value='".$i."'".$selected.'>'.$i.'</option>';
    }
    $form .= '</select>&nbsp;'._MB_NEWS_CAL_MONTH."&nbsp;<select name='options[]'>";
    for ($i=1; $i<=12; $i++) {
        $selected = ($i == $selsmonth) ? "selected='selected'" : '';
        $form .= "<option value='".$i."'".$selected.'>'.$i.'</option>';
    }
    $form .= '</select>';

    $form .= '<br /><br /><b>'._MB_NEWS_ENDING_DATE.'</b><br />';
    $form .= _MB_NEWS_CAL_YEAR."&nbsp;<select name='options[]'>";
    for ($i=$syear; $i<=$eyear; $i++) {
        $selected = ($i == $seleyear) ? "selected='selected'" : '';
        $form .= "<option value='".$i."'".$selected.'>'.$i.'</option>';
    }
    $form .= '</select>&nbsp;'._MB_NEWS_CAL_MONTH."&nbsp;<select name='options[]'>";
    for ($i=1; $i<=12; $i++) {
        $selected = ($i == $selemonth) ? "selected='selected'" : '';
        $form .= "<option value='".$i."'".$selected.'>'.$i.'</option>';
    }
    $form .= '</select>';

    // Or until today *********************************************************
    $form .= '<br />';
    $checked = $options[5] == 1 ? " checked='checked'" : '';
    $form .= "<input type='checkbox' value='1' name='options[]'".$checked.'>';
    $form .= ' <b>'._MB_NEWS_UNTIL_TODAY.'</b>';

    return $form;
}

function b_news_archives_onthefly($options)
{
    $options = explode('|',$options);
    $block = & b_news_archives_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_archives.html');
}
