<?php
// $Id: functions.php 12097 2013-09-26 15:56:34Z beckmi $
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
/**
 * Function used to display a menu similar to the launcher on OS X
 *
 * Enable webmasters to navigate thru the module's features.
 * Each time you select an option in the admin panel of the news module, this option is highlighted in this menu
 *
 * NOTE : Please give credits if you copy this code !
 *
  * @package News
 * @author Hervé Thouzard (http://www.herve-thouzard.com) & Dojo Javscript Toolkit
 * @copyright	(c) Hervé Thouzard (http://www.herve-thouzard.com)
 */

function news_collapsableBar($tablename = '', $iconname = '')
{

    ?>
    <script type="text/javascript"><!--
    function goto_URL(object)
    {
        window.location.href = object.options[object.selectedIndex].value;
    }

    function toggle(id)
    {
        if (document.getElementById) { obj = document.getElementById(id); }
        if (document.all) { obj = document.all[id]; }
        if (document.layers) { obj = document.layers[id]; }
        if (obj) {
            if (obj.style.display == "none") {
                obj.style.display = "";
            } else {
                obj.style.display = "none";
            }
        }

        return false;
    }

    var iconClose = new Image();
    iconClose.src = '../images/icons/close12.gif';
    var iconOpen = new Image();
    iconOpen.src = '../images/icons/open12.gif';

    function toggleIcon ( iconName )
    {
        if (document.images[iconName].src == window.iconOpen.src) {
            document.images[iconName].src = window.iconClose.src;
        } elseif (document.images[iconName].src == window.iconClose.src) {
            document.images[iconName].src = window.iconOpen.src;
        }

        return;
    }

    //-->
    </script>
    <?php
echo "<h4 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}
