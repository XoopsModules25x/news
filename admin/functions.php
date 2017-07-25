<?php
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
 * @copyright      {@link https://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

/**
 * Function used to display a menu similar to the launcher on OS X
 *
 * Enable webmasters to navigate thru the module's features.
 * Each time you select an option in the admin panel of the news module, this option is highlighted in this menu
 *
 * NOTE : Please give credits if you copy this code !
 *
 * @package  ::    News
 * @author   ::     Herve Thouzard (http://www.herve-thouzard.com) & Dojo Javscript Toolkit
 * @copyright::  (c) Herve Thouzard (http://www.herve-thouzard.com)
 * @param string $tablename
 * @param string $iconname
 */

function news_collapsableBar($tablename = '', $iconname = '')
{
    ?>
    <script type="text/javascript">
//        function goto_URL(object) {
//            window.location.href = object.options[object.selectedIndex].value;
//        }
//
//        function toggle(id) {
//            if (document.getElementById) {
//                obj = document.getElementById(id);
//            }
//            if (document.all) {
//                obj = document.all[id];
//            }
//            if (document.layers) {
//                obj = document.layers[id];
//            }
//            if (obj) {
//                if (obj.style.display === "none") {
//                    obj.style.display = "";
//                } else {
//                    obj.style.display = "none";
//                }
//            }
//
//            return false;
//        }
//
//        var iconClose = new Image();
//        iconClose.src = '../images/icons/close12.gif';
//        var iconOpen = new Image();
//        iconOpen.src = '../images/icons/open12.gif';
//
//        function toggleIcon(iconName) {
//            if (document.images[iconName].src == window.iconOpen.src) {
//                document.images[iconName].src = window.iconClose.src;
//            }
//            elseif(document.images[iconName].src == window.iconClose.src) {
//                document.images[iconName].src = window.iconOpen.src;
//            }
//
//            return;
//        }
//

    </script>
    <?php
    echo "<h4 style=\"color: #2F5376; margin: 6px 0 0 0; \"><a href='#' onClick=\"toggle('" . $tablename . "'); toggleIcon('" . $iconname . "');\">";
}
