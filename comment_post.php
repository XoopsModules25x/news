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
 * @license        {@link https://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

use XoopsModules\News;

require_once dirname(__DIR__, 2) . '/mainfile.php';

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();

// We verify that the user can post comments **********************************
if (null === $helper->getModule()) {
    exit();
}

if (0 == $helper->getConfig('com_rule')) { // Comments are deactivated
    exit();
}

if (0 == $helper->getConfig('com_anonpost') && !is_object($xoopsUser)) { // Anonymous users can't post
    exit();
}
// ****************************************************************************
require_once XOOPS_ROOT_PATH . '/include/comment_post.php';
