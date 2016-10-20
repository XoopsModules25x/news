<?php
//
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                  Copyright (c) 2000-2016 XOOPS.org                        //
//                       <http://xoops.org/>                             //
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
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

/**
 * Returns a module's option
 *
 * Return's a module's option (for the news module)
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 *
 * @param string $option module option's name
 *
 * @param string $repmodule
 *
 * @return bool
 */
use WideImage\WideImage;

/**
 * @param             $option
 * @param  string     $repmodule
 * @return bool|mixed
 */
function news_getmoduleoption($option, $repmodule = 'news')
{
    global $xoopsModuleConfig, $xoopsModule;
    static $tbloptions = array();
    if (is_array($tbloptions) && array_key_exists($option, $tbloptions)) {
        return $tbloptions[$option];
    }

    $retval = false;
    if (isset($xoopsModuleConfig)
        && (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $repmodule
            && $xoopsModule->getVar('isactive'))
    ) {
        if (isset($xoopsModuleConfig[$option])) {
            $retval = $xoopsModuleConfig[$option];
        }
    } else {
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler  = xoops_getHandler('module');
        $module         = $moduleHandler->getByDirname($repmodule);
        $config_handler = xoops_getHandler('config');
        if ($module) {
            $moduleConfig = $config_handler->getConfigsByCat(0, $module->getVar('mid'));
            if (isset($moduleConfig[$option])) {
                $retval = $moduleConfig[$option];
            }
        }
    }
    $tbloptions[$option] = $retval;

    return $retval;
}

/**
 * Updates rating data in item table for a given item
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $storyid
 */
function news_updaterating($storyid)
{
    global $xoopsDB;
    $query       = 'SELECT rating FROM ' . $xoopsDB->prefix('news_stories_votedata') . ' WHERE storyid = ' . $storyid;
    $voteresult  = $xoopsDB->query($query);
    $votesDB     = $xoopsDB->getRowsNum($voteresult);
    $totalrating = 0;
    while (list($rating) = $xoopsDB->fetchRow($voteresult)) {
        $totalrating += $rating;
    }
    $finalrating = $totalrating / $votesDB;
    $finalrating = number_format($finalrating, 4);
    $sql         = sprintf('UPDATE %s SET rating = %u, votes = %u WHERE storyid = %u', $xoopsDB->prefix('news_stories'), $finalrating, $votesDB,
                           $storyid);
    $xoopsDB->queryF($sql);
}

/**
 * Internal function for permissions
 *
 * Returns a list of all the permitted topics Ids for the current user
 *
 * @param string $permtype
 *
 * @return array $topics    Permitted topics Ids
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 */
function news_MygetItemIds($permtype = 'news_view')
{
    global $xoopsUser;
    static $tblperms = array();
    if (is_array($tblperms) && array_key_exists($permtype, $tblperms)) {
        return $tblperms[$permtype];
    }

    /** @var XoopsModuleHandler $moduleHandler */
    $moduleHandler       = xoops_getHandler('module');
    $newsModule          = $moduleHandler->getByDirname('news');
    $groups              = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $gperm_handler       = xoops_getHandler('groupperm');
    $topics              = $gperm_handler->getItemIds($permtype, $groups, $newsModule->getVar('mid'));
    $tblperms[$permtype] = $topics;

    return $topics;
}

/**
 * @param $document
 *
 * @return mixed
 */
function news_html2text($document)
{
    // PHP Manual:: function preg_replace
    // $document should contain an HTML document.
    // This will remove HTML tags, javascript sections
    // and white space. It will also convert some
    // common HTML entities to their text equivalent.

    $search = array(
        "'<script[^>]*?>.*?</script>'si", // Strip out javascript
        "'<img.*?/>'si", // Strip out img tags
        "'<[\/\!]*?[^<>]*?>'si", // Strip out HTML tags
        "'([\r\n])[\s]+'", // Strip out white space
        "'&(quot|#34);'i", // Replace HTML entities
        "'&(amp|#38);'i",
        "'&(lt|#60);'i",
        "'&(gt|#62);'i",
        "'&(nbsp|#160);'i",
        "'&(iexcl|#161);'i",
        "'&(cent|#162);'i",
        "'&(pound|#163);'i",
        "'&(copy|#169);'i"
    ); // evaluate as php

    $replace = array(
        '',
        '',
        '',
        "\\1",
        "\"",
        '&',
        '<',
        '>',
        ' ',
        chr(161),
        chr(162),
        chr(163),
        chr(169),
    );

    $text = preg_replace($search, $replace, $document);

    preg_replace_callback('/&#(\d+);/', function ($matches) {
        return chr($matches[1]);
    }, $document);

    return $text;
}

/**
 * Is Xoops 2.3.x ?
 *
 * @return boolean need to say it ?
 */
function news_isX23()
{
    $x23 = false;
    $xv  = str_replace('XOOPS ', '', XOOPS_VERSION);
    if (substr($xv, 2, 1) >= '3') {
        $x23 = true;
    }

    return $x23;
}

/**
 * Retreive an editor according to the module's option "form_options"
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param                                                                                                                                 $caption
 * @param                                                                                                                                 $name
 * @param  string                                                                                                                         $value
 * @param  string                                                                                                                         $width
 * @param  string                                                                                                                         $height
 * @param  string                                                                                                                         $supplemental
 * @return bool|XoopsFormDhtmlTextArea|XoopsFormEditor|XoopsFormFckeditor|XoopsFormHtmlarea|XoopsFormTextArea|XoopsFormTinyeditorTextArea
 */
function news_getWysiwygForm($caption, $name, $value = '', $width = '100%', $height = '400px', $supplemental = '')
{
    $editor_option            = strtolower(news_getmoduleoption('form_options'));
    $editor                   = false;
    $editor_configs           = array();
    $editor_configs['name']   = $name;
    $editor_configs['value']  = $value;
    $editor_configs['rows']   = 35;
    $editor_configs['cols']   = 60;
    $editor_configs['width']  = '100%';
    $editor_configs['height'] = '350px';
    $editor_configs['editor'] = $editor_option;

    if (news_isX23()) {
        $editor = new XoopsFormEditor($caption, $name, $editor_configs);

        return $editor;
    }

    // Only for Xoops 2.0.x
    switch ($editor_option) {
        case 'fckeditor':
            if (is_readable(XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php')) {
                require_once XOOPS_ROOT_PATH . '/class/fckeditor/formfckeditor.php';
                $editor = new XoopsFormFckeditor($caption, $name, $value);
            }
            break;

        case 'htmlarea':
            if (is_readable(XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php')) {
                require_once XOOPS_ROOT_PATH . '/class/htmlarea/formhtmlarea.php';
                $editor = new XoopsFormHtmlarea($caption, $name, $value);
            }
            break;

        case 'dhtmltextarea':
        case 'dhtml':
            $editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
            break;

        case 'textarea':
            $editor = new XoopsFormTextArea($caption, $name, $value);
            break;

        case 'tinyeditor':
        case 'tinymce':
            if (is_readable(XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php')) {
                require_once XOOPS_ROOT_PATH . '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php';
                $editor = new XoopsFormTinyeditorTextArea(array(
                                                              'caption' => $caption,
                                                              'name'    => $name,
                                                              'value'   => $value,
                                                              'width'   => '100%',
                                                              'height'  => '400px'
                                                          ));
            }
            break;

        case 'koivi':
            if (is_readable(XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php')) {
                require_once XOOPS_ROOT_PATH . '/class/wysiwyg/formwysiwygtextarea.php';
                $editor = new XoopsFormWysiwygTextArea($caption, $name, $value, $width, $height, '');
            }
            break;
    }

    return $editor;
}

/**
 * Internal function
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $text
 * @return mixed
 */
function DublinQuotes($text)
{
    return str_replace("\"", ' ', $text);
}

/**
 * Creates all the meta datas :
 * - For Mozilla/Netscape and Opera the site navigation's bar
 * - The Dublin's Core Metadata
 * - The link for Firefox 2 micro summaries
 * - The meta keywords
 * - The meta description
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param null $story
 */
function news_CreateMetaDatas($story = null)
{
    global $xoopsConfig, $xoTheme, $xoopsTpl;
    $content = '';
    $myts    = MyTextSanitizer::getInstance();
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';

    /**
     * Firefox and Opera Navigation's Bar
     */
    if (news_getmoduleoption('sitenavbar')) {
        $content .= sprintf("<link rel=\"Home\" title=\"%s\" href=\"%s/\" />\n", $xoopsConfig['sitename'], XOOPS_URL);
        $content .= sprintf("<link rel=\"Contents\" href=\"%s\" />\n", XOOPS_URL . '/modules/news/index.php');
        $content .= sprintf("<link rel=\"Search\" href=\"%s\" />\n", XOOPS_URL . '/search.php');
        $content .= sprintf("<link rel=\"Glossary\" href=\"%s\" />\n", XOOPS_URL . '/modules/news/archive.php');
        $content .= sprintf("<link rel=\"%s\" href=\"%s\" />\n", $myts->htmlSpecialChars(_NW_SUBMITNEWS), XOOPS_URL . '/modules/news/submit.php');
        $content .= sprintf("<link rel=\"alternate\" type=\"application/rss+xml\" title=\"%s\" href=\"%s/\" />\n", $xoopsConfig['sitename'],
                            XOOPS_URL . '/backend.php');

        // Create chapters
        include_once XOOPS_ROOT_PATH . '/class/tree.php';
        include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
        $xt         = new NewsTopic();
        $allTopics  = $xt->getAllTopics(news_getmoduleoption('restrictindex'));
        $topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        foreach ($topics_arr as $onetopic) {
            $content .= sprintf("<link rel=\"Chapter\" title=\"%s\" href=\"%s\" />\n", $onetopic->topic_title(),
                                XOOPS_URL . '/modules/news/index.php?storytopic=' . $onetopic->topic_id());
        }
    }

    /**
     * Meta Keywords and Description
     * If you have set this module's option to 'yes' and if the information was entered, then they are rendered in the page else they are computed
     */
    $meta_keywords = '';
    if (isset($story) && is_object($story)) {
        if (xoops_trim($story->keywords()) !== '') {
            $meta_keywords = $story->keywords();
        } else {
            $meta_keywords = news_createmeta_keywords($story->hometext() . ' ' . $story->bodytext());
        }
        if (xoops_trim($story->description()) !== '') {
            $meta_description = strip_tags($story->description);
        } else {
            $meta_description = strip_tags($story->title);
        }
        if (isset($xoTheme) && is_object($xoTheme)) {
            $xoTheme->addMeta('meta', 'keywords', $meta_keywords);
            $xoTheme->addMeta('meta', 'description', $meta_description);
        } elseif (isset($xoopsTpl) && is_object($xoopsTpl)) { // Compatibility for old Xoops versions
            $xoopsTpl->assign('xoops_meta_keywords', $meta_keywords);
            $xoopsTpl->assign('xoops_meta_description', $meta_description);
        }
    }

    /**
     * Dublin Core's meta datas
     */
    if (news_getmoduleoption('dublincore') && isset($story) && is_object($story)) {
        $config_handler        = xoops_getHandler('config');
        $xoopsConfigMetaFooter = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        $content .= '<meta name="DC.Title" content="' . DublinQuotes($story->title()) . "\" />\n";
        $content .= '<meta name="DC.Creator" content="' . DublinQuotes($story->uname()) . "\" />\n";
        $content .= '<meta name="DC.Subject" content="' . DublinQuotes($meta_keywords) . "\" />\n";
        $content .= '<meta name="DC.Description" content="' . DublinQuotes($story->title()) . "\" />\n";
        $content .= '<meta name="DC.Publisher" content="' . DublinQuotes($xoopsConfig['sitename']) . "\" />\n";
        $content .= '<meta name="DC.Date.created" scheme="W3CDTF" content="' . date('Y-m-d', $story->created) . "\" />\n";
        $content .= '<meta name="DC.Date.issued" scheme="W3CDTF" content="' . date('Y-m-d', $story->published) . "\" />\n";
        $content .= '<meta name="DC.Identifier" content="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid() . "\" />\n";
        $content .= '<meta name="DC.Source" content="' . XOOPS_URL . "\" />\n";
        $content .= '<meta name="DC.Language" content="' . _LANGCODE . "\" />\n";
        $content .= '<meta name="DC.Relation.isReferencedBy" content="'
                    . XOOPS_URL
                    . '/modules/news/index.php?storytopic='
                    . $story->topicid()
                    . "\" />\n";
        if (isset($xoopsConfigMetaFooter['meta_copyright'])) {
            $content .= '<meta name="DC.Rights" content="' . DublinQuotes($xoopsConfigMetaFooter['meta_copyright']) . "\" />\n";
        }
    }

    /**
     * Firefox 2 micro summaries
     */
    if (news_getmoduleoption('firefox_microsummaries')) {
        $content .= sprintf("<link rel=\"microsummary\" href=\"%s\" />\n", XOOPS_URL . '/modules/news/micro_summary.php');
    }

    if (isset($xoopsTpl) && is_object($xoopsTpl)) {
        $xoopsTpl->assign('xoops_module_header', $content);
    }
}

/**
 * Create the meta keywords based on the content
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $content
 * @return string
 */
function news_createmeta_keywords($content)
{
    include XOOPS_ROOT_PATH . '/modules/news/config.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/blacklist.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/registryfile.php';

    if (!$cfg['meta_keywords_auto_generate']) {
        return '';
    }
    $registry = new news_registryfile('news_metagen_options.txt');
    //    $tcontent = '';
    $tcontent = $registry->getfile();
    if (xoops_trim($tcontent) !== '') {
        list($keywordscount, $keywordsorder) = explode(',', $tcontent);
    } else {
        $keywordscount = $cfg['meta_keywords_count'];
        $keywordsorder = $cfg['meta_keywords_order'];
    }

    $tmp = array();
    // Search for the "Minimum keyword length"
    if (isset($_SESSION['news_keywords_limit'])) {
        $limit = $_SESSION['news_keywords_limit'];
    } else {
        $config_handler                  = xoops_getHandler('config');
        $xoopsConfigSearch               = $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);
        $limit                           = $xoopsConfigSearch['keyword_min'];
        $_SESSION['news_keywords_limit'] = $limit;
    }
    $myts            = MyTextSanitizer::getInstance();
    $content         = str_replace('<br>', ' ', $content);
    $content         = $myts->undoHtmlSpecialChars($content);
    $content         = strip_tags($content);
    $content         = strtolower($content);
    $search_pattern  = array(
        '&nbsp;',
        "\t",
        "\r\n",
        "\r",
        "\n",
        ',',
        '.',
        "'",
        ';',
        ':',
        ')',
        '(',
        '"',
        '?',
        '!',
        '{',
        '}',
        '[',
        ']',
        '<',
        '>',
        '/',
        '+',
        '-',
        '_',
        '\\',
        '*'
    );
    $replace_pattern = array(
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        ' ',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        ''
    );
    $content         = str_replace($search_pattern, $replace_pattern, $content);
    $keywords        = explode(' ', $content);
    switch ($keywordsorder) {
        case 0: // Ordre d'apparition dans le texte
            $keywords = array_unique($keywords);
            break;
        case 1: // Ordre de fréquence des mots
            $keywords = array_count_values($keywords);
            asort($keywords);
            $keywords = array_keys($keywords);
            break;
        case 2: // Ordre inverse de la fréquence des mots
            $keywords = array_count_values($keywords);
            arsort($keywords);
            $keywords = array_keys($keywords);
            break;
    }
    // Remove black listed words
    $metablack = new news_blacklist();
    $words     = $metablack->getAllKeywords();
    $keywords  = $metablack->remove_blacklisted($keywords);

    foreach ($keywords as $keyword) {
        if (strlen($keyword) >= $limit && !is_numeric($keyword)) {
            $tmp[] = $keyword;
        }
    }
    $tmp = array_slice($tmp, 0, $keywordscount);
    if (count($tmp) > 0) {
        return implode(',', $tmp);
    } else {
        if (!isset($config_handler) || !is_object($config_handler)) {
            $config_handler = xoops_getHandler('config');
        }
        $xoopsConfigMetaFooter = $config_handler->getConfigsByCat(XOOPS_CONF_METAFOOTER);
        if (isset($xoopsConfigMetaFooter['meta_keywords'])) {
            return $xoopsConfigMetaFooter['meta_keywords'];
        } else {
            return '';
        }
    }
}

/**
 * Remove module's cache
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 */
function news_updateCache()
{
    global $xoopsModule;
    $folder  = $xoopsModule->getVar('dirname');
    $tpllist = array();
    include_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
    include_once XOOPS_ROOT_PATH . '/class/template.php';
    $tplfile_handler = xoops_getHandler('tplfile');
    $tpllist         = $tplfile_handler->find(null, null, null, $folder);
    $xoopsTpl        = new XoopsTpl();
    xoops_template_clear_module_cache($xoopsModule->getVar('mid')); // Clear module's blocks cache

    // Remove cache for each page.
    foreach ($tpllist as $onetemplate) {
        if ($onetemplate->getVar('tpl_type') === 'module') {
            // Note, I've been testing all the other methods (like the one of Smarty) and none of them run, that's why I have used this code
            $files_del = array();
            $files_del = glob(XOOPS_CACHE_PATH . '/*' . $onetemplate->getVar('tpl_file') . '*');
            if (count($files_del) > 0) {
                foreach ($files_del as $one_file) {
                    unlink($one_file);
                }
            }
        }
    }
}

/**
 * Verify that a mysql table exists
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $tablename
 * @return bool
 */
function news_TableExists($tablename)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Verify that a field exists inside a mysql table
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $fieldname
 * @param $table
 * @return bool
 */
function news_FieldExists($fieldname, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF("SHOW COLUMNS FROM   $table LIKE '$fieldname'");

    return ($xoopsDB->getRowsNum($result) > 0);
}

/**
 * Add a field to a mysql table
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $field
 * @param $table
 * @return
 */
function news_AddField($field, $table)
{
    global $xoopsDB;
    $result = $xoopsDB->queryF('ALTER TABLE ' . $table . " ADD $field;");

    return $result;
}

/**
 * Verify that the current user is a member of the Admin group
 */
function news_is_admin_group()
{
    global $xoopsUser, $xoopsModule;
    if (is_object($xoopsUser)) {
        if (in_array('1', $xoopsUser->getGroups())) {
            return true;
        } else {
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}

/**
 * Verify if the current "user" is a bot or not
 *
 * If you have a problem with this function, insert the folowing code just before the line if (isset($_SESSION['news_cache_bot'])) { :
 * return false;
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 */
function news_isbot()
{
    if (isset($_SESSION['news_cache_bot'])) {
        return $_SESSION['news_cache_bot'];
    } else {
        // Add here every bot you know separated by a pipe | (not matter with the upper or lower cases)
        // If you want to see the result for yourself, add your navigator's user agent at the end (mozilla for example)
        $botlist      = 'AbachoBOT|Arachnoidea|ASPSeek|Atomz|cosmos|crawl25-public.alexa.com|CrawlerBoy Pinpoint.com|Crawler|DeepIndex|EchO!|exabot|Excalibur Internet Spider|FAST-WebCrawler|Fluffy the spider|GAIS Robot/1.0B2|GaisLab data gatherer|Google|Googlebot-Image|googlebot|Gulliver|ia_archiver|Infoseek|Links2Go|Lycos_Spider_(modspider)|Lycos_Spider_(T-Rex)|MantraAgent|Mata Hari|Mercator|MicrosoftPrototypeCrawler|Mozilla@somewhere.com|MSNBOT|NEC Research Agent|NetMechanic|Nokia-WAPToolkit|nttdirectory_robot|Openfind|Oracle Ultra Search|PicoSearch|Pompos|Scooter|Slider_Search_v1-de|Slurp|Slurp.so|SlySearch|Spider|Spinne|SurferF3|Surfnomore Spider|suzuran|teomaagent1|TurnitinBot|Ultraseek|VoilaBot|vspider|W3C_Validator|Web Link Validator|WebTrends|WebZIP|whatUseek_winona|WISEbot|Xenu Link Sleuth|ZyBorg';
        $botlist      = strtoupper($botlist);
        $currentagent = strtoupper(xoops_getenv('HTTP_USER_AGENT'));
        $retval       = false;
        $botarray     = explode('|', $botlist);
        foreach ($botarray as $onebot) {
            if (false !== strpos($currentagent, $onebot)) {
                $retval = true;
                break;
            }
        }
    }
    $_SESSION['news_cache_bot'] = $retval;

    return $retval;
}

/**
 * Create an infotip
 *
 * @package       News
 * @author        Hervé Thouzard (http://www.herve-thouzard.com)
 * @copyright (c) Hervé Thouzard
 * @param $text
 * @return null
 */
function news_make_infotips($text)
{
    $infotips = news_getmoduleoption('infotips');
    if ($infotips > 0) {
        $myts = MyTextSanitizer::getInstance();

        return $myts->htmlSpecialChars(xoops_substr(strip_tags($text), 0, $infotips));
    }

    return null;
}

/**
 * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
 *           <amos dot robinson at gmail dot com>
 * @param $string
 * @return string
 */
function news_close_tags($string)
{
    // match opened tags
    if (preg_match_all('/<([a-z\:\-]+)[^\/]>/', $string, $start_tags)) {
        $start_tags = $start_tags[1];
        // match closed tags
        if (preg_match_all('/<\/([a-z]+)>/', $string, $end_tags)) {
            $complete_tags = array();
            $end_tags      = $end_tags[1];

            foreach ($start_tags as $key => $val) {
                $posb = array_search($val, $end_tags);
                if (is_int($posb)) {
                    unset($end_tags[$posb]);
                } else {
                    $complete_tags[] = $val;
                }
            }
        } else {
            $complete_tags = $start_tags;
        }

        $complete_tags = array_reverse($complete_tags);
        for ($i = 0, $iMax = count($complete_tags); $i < $iMax; ++$i) {
            $string .= '</' . $complete_tags[$i] . '>';
        }
    }

    return $string;
}

/**
 * Smarty truncate_tagsafe modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate_tagsafe<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 *           Makes sure no tags are left half-open or half-closed
 *           (e.g. "Banana in a <a...")
 *
 * @author   Monte Ohrt <monte at ohrt dot com>, modified by Amos Robinson
 *           <amos dot robinson at gmail dot com>
 *
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 *
 * @return string
 */
function news_truncate_tagsafe($string, $length = 80, $etc = '...', $break_words = false)
{
    if ($length == 0) {
        return '';
    }
    if (strlen($string) > $length) {
        $length -= strlen($etc);
        if (!$break_words) {
            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            $string = preg_replace('/<[^>]*$/', '', $string);
            $string = news_close_tags($string);
        }

        return $string . $etc;
    } else {
        return $string;
    }
}

/**
 * Resize a Picture to some given dimensions (using the wideImage library)
 *
 * @param string  $src_path      Picture's source
 * @param string  $dst_path      Picture's destination
 * @param integer $param_width   Maximum picture's width
 * @param integer $param_height  Maximum picture's height
 * @param boolean $keep_original Do we have to keep the original picture ?
 * @param string  $fit           Resize mode (see the wideImage library for more information)
 *
 * @return bool
 */
function news_resizePicture(
    $src_path,
    $dst_path,
    $param_width,
    $param_height,
    $keep_original = false,
    $fit = 'inside'
) {
    //    require_once XOOPS_PATH . '/vendor/wideimage/WideImage.php';
    $resize            = true;
    $pictureDimensions = getimagesize($src_path);
    if (is_array($pictureDimensions)) {
        $pictureWidth  = $pictureDimensions[0];
        $pictureHeight = $pictureDimensions[1];
        if ($pictureWidth < $param_width && $pictureHeight < $param_height) {
            $resize = false;
        }
    }

    $img = WideImage::load($src_path);
    if ($resize) {
        $result = $img->resize($param_width, $param_height, $fit);
        $result->saveToFile($dst_path);
    } else {
        @copy($src_path, $dst_path);
    }
    if (!$keep_original) {
        @unlink($src_path);
    }

    return true;
}
