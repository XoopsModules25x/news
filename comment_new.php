<?php declare(strict_types=1);
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
 * @author         XOOPS Development Team
 */

use Xmf\Request;
use XoopsModules\News;
use XoopsModules\News\NewsStory;

require_once \dirname(__DIR__, 2) . '/mainfile.php';

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();

// require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

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

$com_itemid = Request::getInt('com_itemid', 0, 'GET');
if ($com_itemid > 0) {
    $article = new NewsStory($com_itemid);
    if ($article->storyid > 0) {
        $com_replytext = _POSTEDBY . '&nbsp;<b>' . $article->uname() . '</b>&nbsp;' . _DATE . '&nbsp;<b>' . formatTimestamp($article->published(), News\Utility::getModuleOption('dateformat')) . '</b><br><br>' . $article->hometext();
        $bodytext      = $article->bodytext();
        if ('' !== $bodytext) {
            $com_replytext .= '<br><br>' . $bodytext;
        }
        $com_replytitle = $article->title();
        require XOOPS_ROOT_PATH . '/include/comment_new.php';
    } else {
        exit;
    }
}
