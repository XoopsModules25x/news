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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

use Xmf\Request;
use XoopsModules\News\{
    Helper,
    NewsStory,
    Utility
};

error_reporting(0);

require_once __DIR__ . '/header.php';

$moduleDirName      = basename(__DIR__);
$moduleDirNameUpper = \mb_strtoupper($moduleDirName);

//2.5.8
$helper = Helper::getInstance();
if (\is_file(XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php')) {
    require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';
} else {
    redirect_header($helper->url('index.php'), 3, \constant('CO_' . $moduleDirNameUpper . '_' . 'ERROR_NO_PDF'));
}
$myts = \MyTextSanitizer::getInstance();
// require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';

$storyid = Request::getInt('storyid', 0, 'GET');

if (empty($storyid)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

$article = new NewsStory($storyid);
// Not yet published
if (0 == $article->published() || $article->published() > time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

// Expired
if (0 != $article->expired() && $article->expired() < time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

/** @var \XoopsGroupPermHandler $grouppermHandler */
$grouppermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$grouppermHandler->checkRight('news_view', $article->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}

$dateformat               = Utility::getModuleOption('dateformat');
$article_data             = $article->hometext() . $article->bodytext();
$article_title            = $article->title();
$article_title            = Utility::html2text($myts->undoHtmlSpecialChars($article_title));
$forumdata['topic_title'] = $article_title;
$pdf_data['title']        = $article->title();
$topic_title              = $article->topic_title();
$topic_title              = Utility::html2text($myts->undoHtmlSpecialChars($topic_title));
$pdf_data['subtitle']     = $topic_title;
$pdf_data['subsubtitle']  = $article->subtitle();
$pdf_data['date']         = formatTimestamp($article->published(), $dateformat);
$pdf_data['filename']     = preg_replace('/[^0-9a-z\-_\.]/i', '', htmlspecialchars($article->topic_title(), ENT_QUOTES | ENT_HTML5) . ' - ' . $article->title());
$hometext                 = $article->hometext();
$bodytext                 = $article->bodytext();
$content                  = $myts->undoHtmlSpecialChars($hometext) . '<br><br>' . $myts->undoHtmlSpecialChars($bodytext);
$content                  = str_replace('[pagebreak]', '<br><br>', $content);
$pdf_data['content']      = $content;

$pdf_data['author'] = $article->uname();

//Other stuff
$puff   = '<br>';
$puffer = '<br><br>';

//create the A4-PDF...
$pdf_config['slogan'] = XOOPS_URL . ' - ' . $xoopsConfig['sitename'] . ' - ' . $xoopsConfig['slogan'];

$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, _CHARSET, false);

//$pdf->setLanguageArray($localLanguageOptions);

$pdf->setCreator(PDF_CREATOR);

$pdf->setTitle($pdf_data['title']);
$pdf->setAuthor(PDF_AUTHOR);
$pdf->setSubject($pdf_data['author']);
$out = PDF_AUTHOR . ', ' . $pdf_data['author'] . ', ' . $pdf_data['title'] . ', ' . $pdf_data['subtitle'] . ', ' . $pdf_data['subsubtitle'];
$pdf->setKeywords($out);
$pdf->setAutoPageBreak(true, 25);
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->setHeaderFont(array(PDF_FONT_NAME_SUB, '', PDF_FONT_SIZE_SUB));
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
$pdf->setFooterData($tc = [0, 64, 0], $lc = [0, 64, 128]);
//$pdf->SetHeaderData('','5',$pdf_config['slogan']);
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $pdf_config['slogan'], [0, 64, 255], [0, 64, 128]);
//set margins
$pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

$pdf->Open();
//First page
$pdf->AddPage();
$pdf->setXY(24, 25);
$pdf->setTextColor(10, 60, 160);
//$pdf->SetFont(PDF_FONT_NAME_TITLE, PDF_FONT_STYLE_TITLE, PDF_FONT_SIZE_TITLE);
$pdf->writeHTML($pdf_data['title'] . ' - ' . $pdf_data['subtitle'], K_TITLE_MAGNIFICATION);
//$pdf->Line(25,20,190,20);
if ('' !== $pdf_data['subsubtitle']) {
    $pdf->writeHTML($puff, K_XSMALL_RATIO);
    //    $pdf->SetFont(PDF_FONT_NAME_SUBSUB, PDF_FONT_STYLE_SUBSUB, PDF_FONT_SIZE_SUBSUB);
    $pdf->writeHTML($pdf_data['subsubtitle'], '1');
}
$pdf->writeHTML($puff, '0.2');
//$pdf->SetFont(PDF_FONT_NAME_DATA, PDF_FONT_STYLE_DATA, PDF_FONT_SIZE_DATA);
$out = NEWS_PDF_AUTHOR . ': ' . $pdf_data['author'] . '<br>';
$pdf->writeHTML($out, '0.2');
$out = NEWS_PDF_DATE . ': ' . $pdf_data['date'] . '<br>';
$pdf->writeHTML($out, '0.2');
$pdf->setTextColor(0, 0, 0);
$pdf->writeHTML($puffer, '1');

//$pdf->SetFont(PDF_FONT_NAME_MAIN, PDF_FONT_STYLE_MAIN, PDF_FONT_SIZE_MAIN);
$pdf->writeHTML($pdf_data['content'], $pdf_config['scale']);

//2.5.8
$pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
$pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

$pdf->setFooterData($tc = [0, 64, 0], $lc = [0, 64, 128]);

//initialize document
$pdf->Open();
$pdf->AddPage();
$pdf->writeHTML($content, true, 0, true, 0);

$pdf->Output();
