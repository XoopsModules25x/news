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
 * @copyright    XOOPS Project http://xoops.org/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author       XOOPS Development Team, Kazumi Ono (AKA onokazu)
 */

error_reporting(0);

require_once __DIR__ . '/header.php';
//2.5.8
require_once XOOPS_ROOT_PATH . '/class/libraries/vendor/tecnickcom/tcpdf/tcpdf.php';

$myts = MyTextSanitizer::getInstance();
require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
$storyid = isset($_GET['storyid']) ? (int)$_GET['storyid'] : 0;

if (empty($storyid)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

$article = new NewsStory($storyid);
// Not yet published
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

$dateformat               = news_getmoduleoption('dateformat');
$article_data             = $article->hometext() . $article->bodytext();
$article_title            = $article->title();
$article_title            = news_html2text($myts->undoHtmlSpecialChars($article_title));
$forumdata['topic_title'] = $article_title;
$pdf_data['title']        = $article->title();
$topic_title              = $article->topic_title();
$topic_title              = news_html2text($myts->undoHtmlSpecialChars($topic_title));
$pdf_data['subtitle']     = $topic_title;
$pdf_data['subsubtitle']  = $article->subtitle();
$pdf_data['date']         = formatTimestamp($article->published(), $dateformat);
$pdf_data['filename']     = preg_replace("/[^0-9a-z\-_\.]/i", '', $myts->htmlSpecialChars($article->topic_title()) . ' - ' . $article->title());
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

$pdf->SetCreator(PDF_CREATOR);

$pdf->SetTitle($pdf_data['title']);
$pdf->SetAuthor(PDF_AUTHOR);
$pdf->SetSubject($pdf_data['author']);
$out = PDF_AUTHOR . ', ' . $pdf_data['author'] . ', ' . $pdf_data['title'] . ', ' . $pdf_data['subtitle'] . ', ' . $pdf_data['subsubtitle'];
$pdf->SetKeywords($out);
$pdf->SetAutoPageBreak(true, 25);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);
//$pdf->setHeaderFont(array(PDF_FONT_NAME_SUB, '', PDF_FONT_SIZE_SUB));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));
//$pdf->SetHeaderData('','5',$pdf_config['slogan']);
$pdf->setHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $pdf_config['slogan'], array(0, 64, 255), array(0, 64, 128));
//set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->setHeaderMargin(PDF_MARGIN_HEADER);
$pdf->setFooterMargin(PDF_MARGIN_FOOTER);

$pdf->Open();
//First page
$pdf->AddPage();
$pdf->SetXY(24, 25);
$pdf->SetTextColor(10, 60, 160);
//$pdf->SetFont(PDF_FONT_NAME_TITLE, PDF_FONT_STYLE_TITLE, PDF_FONT_SIZE_TITLE);
$pdf->writeHTML($pdf_data['title'] . ' - ' . $pdf_data['subtitle'], K_TITLE_MAGNIFICATION);
//$pdf->Line(25,20,190,20);
if ($pdf_data['subsubtitle'] !== '') {
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
$pdf->SetTextColor(0, 0, 0);
$pdf->writeHTML($puffer, '1');

//$pdf->SetFont(PDF_FONT_NAME_MAIN, PDF_FONT_STYLE_MAIN, PDF_FONT_SIZE_MAIN);
$pdf->writeHTML($pdf_data['content'], $pdf_config['scale']);


//2.5.8
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->setFooterData($tc = array(0, 64, 0), $lc = array(0, 64, 128));

//initialize document
$pdf->Open();
$pdf->AddPage();
$pdf->writeHTML($content, true, 0, true, 0);




$pdf->Output();
