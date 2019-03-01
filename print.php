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
 * Print an article
 *
 * This page is used to print an article. The advantage of this script is that you
 * only see the article and nothing else.
 *
 * @package               News
 * @author                Xoops Modules Dev Team
 * @copyright    (c)      XOOPS Project (https://xoops.org)
 *
 * Parameters received by this page :
 * @page_param            int        storyid                    Id of news to print
 *
 * @page_title            Story's title - Printer Friendly Page - Topic's title - Site's name
 *
 * @template_name         This page does not use any template
 */

use XoopsModules\News;

require_once dirname(dirname(__DIR__)) . '/mainfile.php';
// require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
$storyid = \Xmf\Request::getInt('storyid', 0, 'GET');
if (empty($storyid)) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

// Verify that the article is published
$story = new \XoopsModules\News\NewsStory($storyid);
// Not yet published
if (0 == $story->published() || $story->published() > time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

// Expired
if (0 != $story->expired() && $story->expired() < time()) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 2, _NW_NOSTORY);
}

// Verify permissions
$grouppermHandler = xoops_getHandler('groupperm');
if (is_object($xoopsUser)) {
    $groups = $xoopsUser->getGroups();
} else {
    $groups = XOOPS_GROUP_ANONYMOUS;
}
if (!$grouppermHandler->checkRight('news_view', $story->topicid(), $groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL . '/modules/news/index.php', 3, _NOPERM);
}

$xoops_meta_keywords    = '';
$xoops_meta_description = '';

if ('' !== trim($story->keywords())) {
    $xoops_meta_keywords = $story->keywords();
} else {
    $xoops_meta_keywords = News\Utility::createMetaKeywords($story->hometext() . ' ' . $story->bodytext());
}

if ('' !== trim($story->description())) {
    $xoops_meta_description = $story->description();
} else {
    $xoops_meta_description = strip_tags($story->title());
}

function PrintPage()
{
    global $xoopsConfig, $xoopsModule, $story, $xoops_meta_keywords, $xoops_meta_description;
    $myts     = \MyTextSanitizer::getInstance();
    $datetime = formatTimestamp($story->published(), News\Utility::getModuleOption('dateformat')); ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
            "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo _LANGCODE; ?>" lang="<?php echo _LANGCODE; ?>">
    <?php
    echo "<head>\n";
    echo '<title>' . $myts->htmlSpecialChars($story->title()) . ' - ' . _NW_PRINTER . ' - ' . $myts->htmlSpecialChars($story->topic_title()) . ' - ' . $xoopsConfig['sitename'] . '</title>';
    echo '<meta http-equiv="Content-Type" content="text/html; charset=' . _CHARSET . '">';
    echo '<meta name="author" content="XOOPS">';
    echo '<meta name="keywords" content="' . $xoops_meta_keywords . '">';
    echo '<meta name="COPYRIGHT" content="Copyright (c) 2014 by ' . $xoopsConfig['sitename'] . '">';
    echo '<meta name="DESCRIPTION" content="' . $xoops_meta_description . '">';
    echo '<meta name="generator" content="XOOPS">';
    echo '<meta name="robots" content="noindex,nofollow">';
    if (file_exists(XOOPS_ROOT_PATH . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css')) {
        echo '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="' . XOOPS_URL . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/style.css">';
    } else {
        echo '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="' . XOOPS_URL . '/language/english/style.css">';
    }
    echo '<link rel="stylesheet" type="text/css" media="all" title="Style sheet" href="' . XOOPS_URL . '/modules/news/assets/css/print.css">';
    $supplemental = '';
    if (News\Utility::getModuleOption('footNoteLinks')) {
        $supplemental = "footnoteLinks('content','content'); "; ?>
        <script type="text/javascript">
            // <![CDATA[
            /*------------------------------------------------------------------------------
             Function:       footnoteLinks()
             Author:         Aaron Gustafson (aaron at easy-designs dot net)
             Creation Date:  8 May 2005
             Version:        1.3
             Homepage:       http://www.easy-designs.net/code/footnoteLinks/
             License:        Creative Commons Attribution-ShareAlike 2.0 License
             http://creativecommons.org/licenses/by-sa/2.0/
             Note:           This version has reduced functionality as it is a demo of
             the script's development
             ------------------------------------------------------------------------------*/
            function footnoteLinks(containerID, targetID) {
                if (!document.getElementById || !document.getElementsByTagName || !document.createElement) return false;
                if (!document.getElementById(containerID) || !document.getElementById(targetID)) return false;
                var container = document.getElementById(containerID);
                var target = document.getElementById(targetID);
                var h2 = document.createElement('h2');
                addClass.apply(h2, ['printOnly']);
                var h2_txt = document.createTextNode('<?php echo _NW_LINKS; ?>');
                h2.appendChild(h2_txt);
                var coll = container.getElementsByTagName('*');
                var ol = document.createElement('ol');
                addClass.apply(ol, ['printOnly']);
                var myArr = [];
                var thisLink;
                var num = 1;
                for (var i = 0; i < coll.length; i++) {
                    if (coll[i].getAttribute('href') ||
                        coll[i].getAttribute('cite')) {
                        thisLink = coll[i].getAttribute('href') ? coll[i].href : coll[i].cite;
                        var note = document.createElement('sup');
                        addClass.apply(note, ['printOnly']);
                        var note_txt;
                        var j = inArray.apply(myArr, [thisLink]);
                        if (j || j === 0) { // if a duplicate
                            // get the corresponding number from
                            // the array of used links
                            note_txt = document.createTextNode(j + 1);
                        } else { // if not a duplicate
                            var li = document.createElement('li');
                            var li_txt = document.createTextNode(thisLink);
                            li.appendChild(li_txt);
                            ol.appendChild(li);
                            myArr.push(thisLink);
                            note_txt = document.createTextNode(num);
                            num++;
                        }
                        note.appendChild(note_txt);
                        if (coll[i].tagName.toLowerCase() === 'blockquote') {
                            var lastChild = lastChildContainingText.apply(coll[i]);
                            lastChild.appendChild(note);
                        } else {
                            coll[i].parentNode.insertBefore(note, coll[i].nextSibling);
                        }
                    }
                }
                target.appendChild(h2);
                target.appendChild(ol);

                return true;
            }

            // ]]>
        </script>
        <script type="text/javascript">
            // <![CDATA[
            /*------------------------------------------------------------------------------
             Excerpts from the jsUtilities Library
             Version:        2.1
             Homepage:       http://www.easy-designs.net/code/jsUtilities/
             License:        Creative Commons Attribution-ShareAlike 2.0 License
             http://creativecommons.org/licenses/by-sa/2.0/
             Note:           If you change or improve on this script, please let us know.
             ------------------------------------------------------------------------------*/
            if (Array.prototype.push === null) {
                Array.prototype.push = function (item) {
                    this[this.length] = item;

                    return this.length;
                };
            }
            // ---------------------------------------------------------------------
            //                  function.apply (if unsupported)
            //           Courtesy of Aaron Boodman - http://youngpup.net
            // ---------------------------------------------------------------------
            if (!Function.prototype.apply) {
                Function.prototype.apply = function (oScope, args) {
                    var sarg = [];
                    var rtrn, call;
                    if (!oScope) oScope = window;
                    if (!args) args = [];
                    for (var i = 0; i < args.length; i++) {
                        sarg[i] = "args[" + i + "]";
                    }
                    call = "oScope.__applyTemp__(" + sarg.join(",") + ");";
                    oScope.__applyTemp__ = this;
                    rtrn = eval(call);
                    oScope.__applyTemp__ = null;

                    return rtrn;
                };
            }

            function inArray(needle) {
                for (var i = 0; i < this.length; i++) {
                    if (this[i] === needle) {
                        return i;
                    }
                }

                return false;
            }

            function addClass(theClass) {
                if (this.className !== '') {
                    this.className += ' ' + theClass;
                } else {
                    this.className = theClass;
                }
            }

            function lastChildContainingText() {
                var testChild = this.lastChild;
                var contentCntnr = ['p', 'li', 'dd'];
                while (testChild.nodeType != 1) {
                    testChild = testChild.previousSibling;
                }
                var tag = testChild.tagName.toLowerCase();
                var tagInArr = inArray.apply(contentCntnr, [tag]);
                if (!tagInArr && tagInArr !== 0) {
                    testChild = lastChildContainingText.apply(testChild);
                }

                return testChild;
            }

            // ]]>
        </script>
        <style type="text/css" media="screen">
            .printOnly {
                display: none;
            }
        </style>
        <?php
    }
    echo '</head>';
    echo '<body bgcolor="#ffffff" text="#000000" onload="' . $supplemental . ' window.print()">
        <div id="content">
        <table border="0"><tr><td align="center">
        <table border="0" width="100%" cellpadding="0" cellspacing="1" bgcolor="#000000"><tr><td>
        <table border="0" width="100%" cellpadding="20" cellspacing="1" bgcolor="#ffffff"><tr><td align="center">
        <img src="' . XOOPS_URL . '/images/logo.png" border="0" alt=""><br><br>
        <h3>' . $story->title() . '</h3>
        <small><b>' . _NW_DATE . '</b>&nbsp;' . $datetime . ' | <b>' . _NW_TOPICC . '</b>&nbsp;' . $myts->htmlSpecialChars($story->topic_title()) . '</small><br><br></td></tr>';
    echo '<tr valign="top" style="font:12px;"><td>' . $story->hometext() . '<br>';
    $bodytext = $story->bodytext();
    $bodytext = str_replace('[pagebreak]', '<br style="page-break-after:always;">', $bodytext);
    if ('' !== $bodytext) {
        echo $bodytext . '<br><br>';
    }
    echo '</td></tr></table></td></tr></table>
    <br><br>';
    printf(_NW_THISCOMESFROM, htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
    echo '<br><a href="' . XOOPS_URL . '/">' . XOOPS_URL . '</a><br><br>
        ' . _NW_URLFORSTORY . ' <!-- Tag below can be used to display Permalink image --><!--img src="' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/assets/images/x.gif" /--><br>
        <a class="ignore" href="' . XOOPS_URL . '/modules/' . $xoopsModule->dirname() . '/article.php?storyid=' . $story->storyid() . '">' . XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid() . '</a>
        </td></tr></table></div>
        </body>
        </html>
        ';
}

PrintPage();
