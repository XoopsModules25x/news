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
 * @copyright      {@link http://xoops.org/ XOOPS Project}
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

include __DIR__ . '/../../mainfile.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

$fileid = isset($_GET['fileid']) ? (int)$_GET['fileid'] : 0;
if (empty($fileid)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _ERRORS);
}
$myts   = MyTextSanitizer::getInstance(); // MyTextSanitizer object
$sfiles = new sFiles($fileid);

// Do we have the right to see the file ?
$article = new NewsStory($sfiles->getStoryid());
// and the news, can we see it ?
if ($article->published() == 0 || $article->published() > time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}
// Expired
if ($article->expired() != 0 && $article->expired() < time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

$gpermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$gpermHandler->checkRight('news_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}

$sfiles->updateCounter();
$url = XOOPS_UPLOAD_URL . '/' . $sfiles->getDownloadname();
if (!preg_match("/^ed2k*:\/\//i", $url)) {
    header("Location: $url");
}
echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=" . $myts->htmlSpecialChars($url) . "\"></meta></head><body></body></html>";
exit();
