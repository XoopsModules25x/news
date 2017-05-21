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

// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');
$moduleDirName = basename(__DIR__);

$modversion['version']       = 1.72;
$modversion['module_status'] = 'RC 1';
$modversion['release_date']  = '2017/05/20';
$modversion['name']          = _MI_NEWS_NAME;
$modversion['description']   = _MI_NEWS_DESC;
$modversion['credits']       = 'XOOPS Project, Christian, Pilou, Marco, <br>ALL the members of the Newbb Team, GIJOE, Zoullou, Mithrandir, <br>Setec Astronomy, Marcan, 5vision, Anne, Trabis, dhsoft, Mamba, Mage, Timgno';
$modversion['author']        = 'XOOPS Project Module Dev Team & Hervé Thouzard';
$modversion['nickname']      = 'hervet';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU General Public License';
$modversion['license_url']   = 'http://www.gnu.org/licenses/gpl.html';
$modversion['official']      = 0; //1 indicates supported by XOOPS Dev Team, 0 means 3rd party supported
$modversion['image']         = 'assets/images/logoModule.png';
$modversion['dirname']       = $moduleDirName;
//$modversion['dirmoduleadmin']      = '/Frameworks/moduleclasses/moduleadmin';
//$modversion['icons16']             = '../../Frameworks/moduleclasses/icons/16';
//$modversion['icons32']             = '../../Frameworks/moduleclasses/icons/32';
$modversion['onInstall']           = 'include/install_function.php';
$modversion['onUpdate']            = 'include/update_function.php';
$modversion['module_website_url']  = 'www.xoops.org/';
$modversion['module_website_name'] = 'XOOPS';
$modversion['author_website_url']  = 'http://xoops.org/';
$modversion['author_website_name'] = 'XOOPS';
$modversion['min_php']             = '5.5';
$modversion['min_xoops']           = '2.5.8';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = array('mysql' => '5.5');

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
$modversion['tables'][0] = 'news_stories';
$modversion['tables'][1] = 'news_topics';
$modversion['tables'][2] = 'news_stories_files';
$modversion['tables'][3] = 'news_stories_votedata';

// Scripts to run upon installation or update
//$modversion['onInstall']['file'] = "include/install_function.php";
//$modversion['onInstall']['func'] = "xoops_module_install_news";
//$modversion['onUpdate'] = "include/update_function.php";

// Admin things
$modversion['hasAdmin']    = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex']  = 'admin/index.php';
$modversion['adminmenu']   = 'admin/menu.php';

// Templates
$i                                          = 1;
$modversion['templates'][$i]['file']        = 'news_item.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'news_archive.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'news_article.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'news_index.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'news_by_topic.tpl';
$modversion['templates'][$i]['description'] = '';
++$i;
$modversion['templates'][$i]['file']        = 'news_by_this_author.tpl';
$modversion['templates'][$i]['description'] = 'Shows a page resuming all the articles of the same author (according to the perms)';
++$i;
$modversion['templates'][$i]['file']        = 'news_ratenews.tpl';
$modversion['templates'][$i]['description'] = 'Template used to rate a news';
++$i;
$modversion['templates'][$i]['file']        = 'news_rss.tpl';
$modversion['templates'][$i]['description'] = 'Used for RSS per topics';
++$i;
$modversion['templates'][$i]['file']        = 'news_whos_who.tpl';
$modversion['templates'][$i]['description'] = "Who's who";
++$i;
$modversion['templates'][$i]['file']        = 'news_topics_directory.tpl';
$modversion['templates'][$i]['description'] = 'Topics Directory';

// Blocks
$modversion['blocks'][1]['file']        = 'news_topics.php';
$modversion['blocks'][1]['name']        = _MI_NEWS_BNAME1;
$modversion['blocks'][1]['description'] = 'Shows news topics';
$modversion['blocks'][1]['show_func']   = 'b_news_topics_show';
$modversion['blocks'][1]['template']    = 'news_block_topics.tpl';

$modversion['blocks'][2]['file']        = 'news_bigstory.php';
$modversion['blocks'][2]['name']        = _MI_NEWS_BNAME3;
$modversion['blocks'][2]['description'] = 'Shows most read story of the day';
$modversion['blocks'][2]['show_func']   = 'b_news_bigstory_show';
$modversion['blocks'][2]['template']    = 'news_block_bigstory.tpl';

$modversion['blocks'][3]['file']        = 'news_top.php';
$modversion['blocks'][3]['name']        = _MI_NEWS_BNAME4;
$modversion['blocks'][3]['description'] = 'Shows top read news articles';
$modversion['blocks'][3]['show_func']   = 'b_news_top_show';
$modversion['blocks'][3]['edit_func']   = 'b_news_top_edit';
$modversion['blocks'][3]['options']     = 'counter|10|25|0|0|0|0||1||||||';
$modversion['blocks'][3]['template']    = 'news_block_top.tpl';

$modversion['blocks'][4]['file']        = 'news_top.php';
$modversion['blocks'][4]['name']        = _MI_NEWS_BNAME5;
$modversion['blocks'][4]['description'] = 'Shows recent articles';
$modversion['blocks'][4]['show_func']   = 'b_news_top_show';
$modversion['blocks'][4]['edit_func']   = 'b_news_top_edit';
$modversion['blocks'][4]['options']     = 'published|10|25|0|0|0|0||1||||||';
$modversion['blocks'][4]['template']    = 'news_block_top.tpl';

$modversion['blocks'][5]['file']        = 'news_moderate.php';
$modversion['blocks'][5]['name']        = _MI_NEWS_BNAME6;
$modversion['blocks'][5]['description'] = 'Shows a block to moderate articles';
$modversion['blocks'][5]['show_func']   = 'b_news_topics_moderate';
$modversion['blocks'][5]['template']    = 'news_block_moderate.tpl';

$modversion['blocks'][6]['file']        = 'news_topicsnav.php';
$modversion['blocks'][6]['name']        = _MI_NEWS_BNAME7;
$modversion['blocks'][6]['description'] = 'Shows a block to navigate topics';
$modversion['blocks'][6]['show_func']   = 'b_news_topicsnav_show';
$modversion['blocks'][6]['template']    = 'news_block_topicnav.tpl';
$modversion['blocks'][6]['options']     = '0';
$modversion['blocks'][6]['edit_func']   = 'b_news_topicsnav_edit';

$modversion['blocks'][7]['file']        = 'news_randomnews.php';
$modversion['blocks'][7]['name']        = _MI_NEWS_BNAME8;
$modversion['blocks'][7]['description'] = 'Shows a block where news appears randomly';
$modversion['blocks'][7]['show_func']   = 'b_news_randomnews_show';
$modversion['blocks'][7]['template']    = 'news_block_randomnews.tpl';
$modversion['blocks'][7]['options']     = 'published|10|25|0|0';
$modversion['blocks'][7]['edit_func']   = 'b_news_randomnews_edit';

$modversion['blocks'][8]['file']        = 'news_archives.php';
$modversion['blocks'][8]['name']        = _MI_NEWS_BNAME9;
$modversion['blocks'][8]['description'] = 'Shows a block where you can see archives';
$modversion['blocks'][8]['show_func']   = 'b_news_archives_show';
$modversion['blocks'][8]['template']    = 'news_block_archives.tpl';
$modversion['blocks'][8]['options']     = '0|0|0|0|1|1'; // Starting date (year, month), ending date (year, month), until today, sort order
$modversion['blocks'][8]['edit_func']   = 'b_news_archives_edit';

// Added in v1.63
$modversion['blocks'][9]['file']        = 'news_block_tag.php';
$modversion['blocks'][9]['name']        = _MI_NEWS_BNAME10;
$modversion['blocks'][9]['description'] = 'Show tag cloud';
$modversion['blocks'][9]['show_func']   = 'news_tag_block_cloud_show';
$modversion['blocks'][9]['edit_func']   = 'news_tag_block_cloud_edit';
$modversion['blocks'][9]['options']     = '100|0|150|80';
$modversion['blocks'][9]['template']    = 'news_tag_block_cloud.tpl';

$modversion['blocks'][10]['file']        = 'news_block_tag.php';
$modversion['blocks'][10]['name']        = _MI_NEWS_BNAME11;
$modversion['blocks'][10]['description'] = 'Show top tags';
$modversion['blocks'][10]['show_func']   = 'news_tag_block_top_show';
$modversion['blocks'][10]['edit_func']   = 'news_tag_block_top_edit';
$modversion['blocks'][10]['options']     = '50|30|c';
$modversion['blocks'][10]['template']    = 'news_tag_block_top.tpl';

// Menu
$modversion['hasMain'] = 1;

$cansubmit = 0;

/**
 * This part inserts the selected topics as sub items in the Xoops main menu
 */
/** @var XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($modversion['dirname']);
if ($module) {
    global $xoopsUser;
    if (is_object($xoopsUser)) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    $gpermHandler = xoops_getHandler('groupperm');
    if ($gpermHandler->checkRight('news_submit', 0, $groups, $module->getVar('mid'))) {
        $cansubmit = 1;
    }
}

// ************
$i = 1;
global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
// We try to "win" some time
// 1)  Check to see it the module is the current module
if (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modversion['dirname']
    && $xoopsModule->getVar('isactive')
) {
    // 2) If there's no topics to display as sub menus we can go on
    if (!isset($_SESSION['items_count']) || $_SESSION['items_count'] == -1) {
        $sql    = 'SELECT COUNT(*) AS cpt FROM ' . $xoopsDB->prefix('news_topics') . ' WHERE menu=1';
        $result = $xoopsDB->query($sql);
        list($count) = $xoopsDB->fetchRow($result);
        $_SESSION['items_count'] = $count;
    } else {
        $count = $_SESSION['items_count'];
    }
    if ($count > 0) {
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
        require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
        $xt         = new NewsTopic();
        $allTopics  = $xt->getAllTopics(news_getmoduleoption('restrictindex'));
        $topic_tree = new XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        if ($module) {
            foreach ($topics_arr as $onetopic) {
                if ($gpermHandler->checkRight('news_view', $onetopic->topic_id(), $groups, $xoopsModule->getVar('mid'))
                    && $onetopic->menu()
                ) {
                    $modversion['sub'][$i]['name'] = $onetopic->topic_title();
                    $modversion['sub'][$i]['url']  = 'index.php?storytopic=' . $onetopic->topic_id();
                }
                ++$i;
            }
        }
        unset($xt);
    }
}

$modversion['sub'][$i]['name'] = _MI_NEWS_SMNAME2;
$modversion['sub'][$i]['url']  = 'archive.php';
if ($cansubmit) {
    ++$i;
    $modversion['sub'][$i]['name'] = _MI_NEWS_SMNAME1;
    $modversion['sub'][$i]['url']  = 'submit.php';
}
unset($cansubmit);

require_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
if (news_getmoduleoption('newsbythisauthor')) {
    ++$i;
    $modversion['sub'][$i]['name'] = _MI_NEWS_WHOS_WHO;
    $modversion['sub'][$i]['url']  = 'whoswho.php';
}

++$i;
$modversion['sub'][$i]['name'] = _MI_NEWS_TOPICS_DIRECTORY;
$modversion['sub'][$i]['url']  = 'topics_directory.php';

// Search
$modversion['hasSearch']      = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'news_search';

// Comments
$modversion['hasComments']          = 1;
$modversion['comments']['pageName'] = 'article.php';
$modversion['comments']['itemName'] = 'storyid';
// Comment callback functions
$modversion['comments']['callbackFile']        = 'include/comment_functions.php';
$modversion['comments']['callback']['approve'] = 'news_com_approve';
$modversion['comments']['callback']['update']  = 'news_com_update';

// start module optins
$i = 0;
/**
 * Select the number of news items to display on top page
 */
++$i;
$modversion['config'][$i]['name']        = 'storyhome';
$modversion['config'][$i]['title']       = '_MI_STORYHOME';
$modversion['config'][$i]['description'] = '_MI_STORYHOMEDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 5;
$modversion['config'][$i]['options']     = array('5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30);

/**
 * Format of the date to use in the module, if you don't specify anything then the default date's format will be used
 */
++$i;
$modversion['config'][$i]['name']        = 'dateformat';
$modversion['config'][$i]['title']       = '_MI_NEWS_DATEFORMAT';
$modversion['config'][$i]['description'] = '_MI_NEWS_DATEFORMAT_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

/**
 * Display a navigation's box on the pages ?
 * This navigation's box enable you to jump from one topic to another
 */
++$i;
$modversion['config'][$i]['name']        = 'displaynav';
$modversion['config'][$i]['title']       = '_MI_DISPLAYNAV';
$modversion['config'][$i]['description'] = '_MI_DISPLAYNAVDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

/*
 ++$i;
 $modversion['config'][$i]['name'] = 'anonpost';
 $modversion['config'][$i]['title'] = '_MI_ANONPOST';
 $modversion['config'][$i]['description'] = '';
 $modversion['config'][$i]['formtype'] = 'yesno';
 $modversion['config'][$i]['valuetype'] = 'int';
 $modversion['config'][$i]['default'] = 0;
 */

/**
 * Auto approuve submited stories
 */
++$i;
$modversion['config'][$i]['name']        = 'autoapprove';
$modversion['config'][$i]['title']       = '_MI_AUTOAPPROVE';
$modversion['config'][$i]['description'] = '_MI_AUTOAPPROVEDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Dispay layout, classic or by topics
 */
++$i;
$modversion['config'][$i]['name']        = 'newsdisplay';
$modversion['config'][$i]['title']       = '_MI_NEWSDISPLAY';
$modversion['config'][$i]['description'] = '_MI_NEWSDISPLAYDESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'Classic';
$modversion['config'][$i]['options']     = array('_MI_NEWSCLASSIC' => 'Classic', '_MI_NEWSBYTOPIC' => 'Bytopic');

/**
 * How to display Author's name, username, full name or nothing ?
 */
++$i;
$modversion['config'][$i]['name']        = 'displayname';
$modversion['config'][$i]['title']       = '_MI_NAMEDISPLAY';
$modversion['config'][$i]['description'] = '_MI_ADISPLAYNAMEDSC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = array(
    '_MI_DISPLAYNAME1' => 1,
    '_MI_DISPLAYNAME2' => 2,
    '_MI_DISPLAYNAME3' => 3
);

/**
 * Number of columns to use to display news
 */
++$i;
$modversion['config'][$i]['name']        = 'columnmode';
$modversion['config'][$i]['title']       = '_MI_COLUMNMODE';
$modversion['config'][$i]['description'] = '_MI_COLUMNMODE_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;
$modversion['config'][$i]['options']     = array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);

/**
 * Number of news and topics to display in the module's admin part
 */
++$i;
$modversion['config'][$i]['name']        = 'storycountadmin';
$modversion['config'][$i]['title']       = '_MI_STORYCOUNTADMIN';
$modversion['config'][$i]['description'] = '_MI_STORYCOUNTADMIN_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 10;
$modversion['config'][$i]['options']     = array(
    '5'  => 5,
    '10' => 10,
    '15' => 15,
    '20' => 20,
    '25' => 25,
    '30' => 30,
    '35' => 35,
    '40' => 40
);

/**
 * Authorized groups to upload
 */
++$i;
$modversion['config'][$i]['name']        = 'uploadgroups';
$modversion['config'][$i]['title']       = '_MI_UPLOADGROUPS';
$modversion['config'][$i]['description'] = '_MI_UPLOADGROUPS_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 2;
$modversion['config'][$i]['options']     = array(
    '_MI_UPLOAD_GROUP1' => 1,
    '_MI_UPLOAD_GROUP2' => 2,
    '_MI_UPLOAD_GROUP3' => 3
);

/**
 * MAX Filesize Upload in kilo bytes
 */
++$i;
$modversion['config'][$i]['name']        = 'maxuploadsize';
$modversion['config'][$i]['title']       = '_MI_UPLOADFILESIZE';
$modversion['config'][$i]['description'] = '_MI_UPLOADFILESIZE_DESC';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1048576;

/**
 * Display  Topic_title with news_title  ?
 * display Topic_title right before news_title in  news_item.tpl
 */
++$i;
$modversion['config'][$i]['name']        = 'displaytopictitle';
$modversion['config'][$i]['title']       = '_MI_DISPLAYTOPIC_TITLE';
$modversion['config'][$i]['description'] = '_MI_DISPLAYTOPIC_TITLEDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

/**
 * Restrict Topics on Index Page
 *
 * This is one of the mot important option in the module.
 * If you set it to No, then the users can see the introduction's text of each
 * story even if they don't have the right to see the topic attached to the news.
 * If you set it to Yes then you can only see what you have the right to see.
 * Many of the permissions are based on this option.
 */
++$i;
$modversion['config'][$i]['name']        = 'restrictindex';
$modversion['config'][$i]['title']       = '_MI_RESTRICTINDEX';
$modversion['config'][$i]['description'] = '_MI_RESTRICTINDEXDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Do you want to enable your visitors to see all the other articles
 * created by the author they are currently reading ?
 */
++$i;
$modversion['config'][$i]['name']        = 'newsbythisauthor';
$modversion['config'][$i]['title']       = '_MI_NEWSBYTHISAUTHOR';
$modversion['config'][$i]['description'] = '_MI_NEWSBYTHISAUTHORDSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * If you set this option to yes then you will see two links at the bottom
 * of each article. The first link will enable you to go to the previous
 * article and the other link will bring you to the next article
 */
++$i;
$modversion['config'][$i]['name']        = 'showprevnextlink';
$modversion['config'][$i]['title']       = '_MI_NEWS_PREVNEX_LINK';
$modversion['config'][$i]['description'] = '_MI_NEWS_PREVNEX_LINK_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Do you want to see a summary table at the bottom of each article ?
 */
++$i;
$modversion['config'][$i]['name']        = 'showsummarytable';
$modversion['config'][$i]['title']       = '_MI_NEWS_SUMMARY_SHOW';
$modversion['config'][$i]['description'] = '_MI_NEWS_SUMMARY_SHOW_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Do you enable author's to edit their posts ?
 */
++$i;
$modversion['config'][$i]['name']        = 'authoredit';
$modversion['config'][$i]['title']       = '_MI_NEWS_AUTHOR_EDIT';
$modversion['config'][$i]['description'] = '_MI_NEWS_AUTHOR_EDIT_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

/**
 * Do you want to enable your visitors to rate news ?
 */
++$i;
$modversion['config'][$i]['name']        = 'ratenews';
$modversion['config'][$i]['title']       = '_MI_NEWS_RATE_NEWS';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * You can set RSS feeds per topic
 */
++$i;
$modversion['config'][$i]['name']        = 'topicsrss';
$modversion['config'][$i]['title']       = '_MI_NEWS_TOPICS_RSS';
$modversion['config'][$i]['description'] = '_MI_NEWS_TOPICS_RSS_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * If you set this option to yes then the approvers can type the keyword
 * and description's meta datas
 */
++$i;
$modversion['config'][$i]['name']        = 'metadata';
$modversion['config'][$i]['title']       = '_MI_NEWS_META_DATA';
$modversion['config'][$i]['description'] = '_MI_NEWS_META_DATA_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Editor to use
 */
++$i;
$modversion['config'][$i]['name']        = 'form_options';
$modversion['config'][$i]['title']       = '_MI_NEWS_FORM_OPTIONS';
$modversion['config'][$i]['description'] = '_MI_NEWS_FORM_OPTIONS_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = 'dhtml';
xoops_load('xoopseditorhandler');
$editorHandler                      = XoopsEditorHandler::getInstance();
$modversion['config'][$i]['options'] = array_flip($editorHandler->getList());

/**
 * If you set this option to Yes then the keywords entered in the
 * search will be highlighted in the articles.
 */
++$i;
$modversion['config'][$i]['name']        = 'keywordshighlight';
$modversion['config'][$i]['title']       = '_MI_NEWS_KEYWORDS_HIGH';
$modversion['config'][$i]['description'] = '_MI_NEWS_KEYWORDS_HIGH_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * If you have enabled the previous option then with this one
 * you can select the color to use to highlight words
 */
++$i;
$modversion['config'][$i]['name']        = 'highlightcolor';
$modversion['config'][$i]['title']       = '_MI_NEWS_HIGH_COLOR';
$modversion['config'][$i]['description'] = '_MI_NEWS_HIGH_COLOR_DES';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '#FFFF80';

/**
 * Tooltips, or infotips are some small textes you can see when you
 * move your mouse over an article's title. This text contains the
 * first (x) characters of the story
 */
++$i;
$modversion['config'][$i]['name']        = 'infotips';
$modversion['config'][$i]['title']       = '_MI_NEWS_INFOTIPS';
$modversion['config'][$i]['description'] = '_MI_NEWS_INFOTIPS_DES';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = '0';

/**
 * This option is specific to Mozilla/Firefox and Opera
 * Both of them can display a toolbar wich contains buttons to
 * go from article to article. It can show other information too
 */
++$i;
$modversion['config'][$i]['name']        = 'sitenavbar';
$modversion['config'][$i]['title']       = '_MI_NEWS_SITE_NAVBAR';
$modversion['config'][$i]['description'] = '_MI_NEWS_SITE_NAVBAR_DESC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * With this option you can select the skin (apparence) to use for the blocks containing tabs
 */
++$i;
$modversion['config'][$i]['name']        = 'tabskin';
$modversion['config'][$i]['title']       = '_MI_NEWS_TABS_SKIN';
$modversion['config'][$i]['description'] = '_MI_NEWS_TABS_SKIN_DESC';
$modversion['config'][$i]['formtype']    = 'select';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['options']     = array(
    _MI_NEWS_SKIN_1 => 1,
    _MI_NEWS_SKIN_2 => 2,
    _MI_NEWS_SKIN_3 => 3,
    _MI_NEWS_SKIN_4 => 4,
    _MI_NEWS_SKIN_5 => 5,
    _MI_NEWS_SKIN_6 => 6,
    _MI_NEWS_SKIN_7 => 7,
    _MI_NEWS_SKIN_8 => 8
);
$modversion['config'][$i]['default']     = 6;

/**
 * Display a navigation's box on the pages ?
 * This navigation's box enable you to jump from one topic to another
 */
++$i;
$modversion['config'][$i]['name']        = 'footNoteLinks';
$modversion['config'][$i]['title']       = '_MI_NEWS_FOOTNOTES';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

/**
 * Activate Dublin Core Metadata ?
 */
++$i;
$modversion['config'][$i]['name']        = 'dublincore';
$modversion['config'][$i]['title']       = '_MI_NEWS_DUBLINCORE';
$modversion['config'][$i]['description'] = '_MI_NEWS_DUBLINCORE_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Display a "Bookmark this article at these sites" block ?
 */
++$i;
$modversion['config'][$i]['name']        = 'bookmarkme';
$modversion['config'][$i]['title']       = '_MI_NEWS_BOOKMARK_ME';
$modversion['config'][$i]['description'] = '_MI_NEWS_BOOKMARK_ME_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Activate Firefox 2 microformats ? //obsolete, see here : http://wiki.mozilla.org/Microsummaries (cesagonchu)
 * ++$i;
 * $modversion['config'][$i]['name'] = 'firefox_microsummaries';
 * $modversion['config'][$i]['title'] = '_MI_NEWS_FF_MICROFORMAT';
 * $modversion['config'][$i]['description'] = '_MI_NEWS_FF_MICROFORMAT_DSC';
 * $modversion['config'][$i]['formtype'] = 'yesno';
 * $modversion['config'][$i]['valuetype'] = 'int';
 * $modversion['config'][$i]['default'] = 0;
 */

/**
 * Advertisement
 */
++$i;
$modversion['config'][$i]['name']        = 'advertisement';
$modversion['config'][$i]['title']       = '_MI_NEWS_ADVERTISEMENT';
$modversion['config'][$i]['description'] = '_MI_NEWS_ADV_DESCR';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

/**
 * Mime Types
 *
 * Default values : Web pictures (png, gif, jpeg), zip, pdf, gtar, tar, pdf
 */
++$i;
$modversion['config'][$i]['name']        = 'mimetypes';
$modversion['config'][$i]['title']       = '_MI_NEWS_MIME_TYPES';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = "image/gif\nimage/jpeg\nimage/pjpeg\nimage/x-png\nimage/png\napplication/x-zip-compressed\napplication/zip\napplication/pdf\napplication/x-gtar\napplication/x-tar";

/**
 * Use enhanced page separator ?
 */
++$i;
$modversion['config'][$i]['name']        = 'enhanced_pagenav';
$modversion['config'][$i]['title']       = '_MI_NEWS_ENHANCED_PAGENAV';
$modversion['config'][$i]['description'] = '_MI_NEWS_ENHANCED_PAGENAV_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Use the TAGS system ?
 */
++$i;
$modversion['config'][$i]['name']        = 'tags';
$modversion['config'][$i]['title']       = '_MI_NEWS_TAGS';
$modversion['config'][$i]['description'] = '_MI_NEWS_TAGS_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Introduction text to show on the submit page
 */
++$i;
$modversion['config'][$i]['name']        = 'submitintromsg';
$modversion['config'][$i]['title']       = '_MI_NEWS_INTRO_TEXT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textarea';
$modversion['config'][$i]['valuetype']   = 'text';
$modversion['config'][$i]['default']     = '';

/**
 * Max width
 */
++$i;
$modversion['config'][$i]['name']        = 'maxwidth';
$modversion['config'][$i]['title']       = '_MI_NEWS_IMAGE_MAX_WIDTH';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 640;

/**
 * Max height
 */
++$i;
$modversion['config'][$i]['name']        = 'maxheight';
$modversion['config'][$i]['title']       = '_MI_NEWS_IMAGE_MAX_HEIGHT';
$modversion['config'][$i]['description'] = '';
$modversion['config'][$i]['formtype']    = 'textbox';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 480;

/**
 * Display a "share" items ?
 */
++$i;
$modversion['config'][$i]['name']        = 'share';
$modversion['config'][$i]['title']       = '_MI_NEWS_SHARE_ME';
$modversion['config'][$i]['description'] = '_MI_NEWS_SHARE_ME_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Display Print and Email icons in each article ?
 */
++$i;
$modversion['config'][$i]['name']        = 'showicons';
$modversion['config'][$i]['title']       = '_MI_NEWS_SHOWICONS';
$modversion['config'][$i]['description'] = '_MI_NEWS_SHOWICONS_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 1;

/**
 * Display PDF icon in each article ?
 */

++$i;
$modversion['config'][$i]['name']        = 'show_pdficon';
$modversion['config'][$i]['title']       = '_MI_NEWS_SHOWICONS_PDF';
$modversion['config'][$i]['description'] = '_MI_NEWS_SHOWICONS_PDF_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

/**
 * Allow Facebook Comments?
 */
++$i;
$modversion['config'][$i]['name']        = 'fbcomments';
$modversion['config'][$i]['title']       = '_MI_NEWS_FACEBOOKCOMMENTS';
$modversion['config'][$i]['description'] = '_MI_NEWS_FACEBOOKCOMMENTS_DSC';
$modversion['config'][$i]['formtype']    = 'yesno';
$modversion['config'][$i]['valuetype']   = 'int';
$modversion['config'][$i]['default']     = 0;

// Notification
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'news_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_NEWS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_NEWS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = array('index.php', 'article.php');

$modversion['notification']['category'][2]['name']           = 'story';
$modversion['notification']['category'][2]['title']          = _MI_NEWS_STORY_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_NEWS_STORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = array('article.php');
$modversion['notification']['category'][2]['item_name']      = 'storyid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

// Added by Lankford on 2007/3/23
$modversion['notification']['category'][3]['name']           = 'category';
$modversion['notification']['category'][3]['title']          = _MI_NEWS_CATEGORY_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_NEWS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = array('index.php', 'article.php');
$modversion['notification']['category'][3]['item_name']      = 'storytopic';
$modversion['notification']['category'][3]['allow_bookmark'] = 1;

$modversion['notification']['event'][1]['name']          = 'new_category';
$modversion['notification']['event'][1]['category']      = 'global';
$modversion['notification']['event'][1]['title']         = _MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFY;
$modversion['notification']['event'][1]['caption']       = _MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYCAP;
$modversion['notification']['event'][1]['description']   = _MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYDSC;
$modversion['notification']['event'][1]['mail_template'] = 'global_newcategory_notify';
$modversion['notification']['event'][1]['mail_subject']  = _MI_NEWS_GLOBAL_NEWCATEGORY_NOTIFYSBJ;

$modversion['notification']['event'][2]['name']          = 'story_submit';
$modversion['notification']['event'][2]['category']      = 'global';
$modversion['notification']['event'][2]['admin_only']    = 1;
$modversion['notification']['event'][2]['title']         = _MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFY;
$modversion['notification']['event'][2]['caption']       = _MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYCAP;
$modversion['notification']['event'][2]['description']   = _MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYDSC;
$modversion['notification']['event'][2]['mail_template'] = 'global_storysubmit_notify';
$modversion['notification']['event'][2]['mail_subject']  = _MI_NEWS_GLOBAL_STORYSUBMIT_NOTIFYSBJ;

$modversion['notification']['event'][3]['name']          = 'new_story';
$modversion['notification']['event'][3]['category']      = 'global';
$modversion['notification']['event'][3]['title']         = _MI_NEWS_GLOBAL_NEWSTORY_NOTIFY;
$modversion['notification']['event'][3]['caption']       = _MI_NEWS_GLOBAL_NEWSTORY_NOTIFYCAP;
$modversion['notification']['event'][3]['description']   = _MI_NEWS_GLOBAL_NEWSTORY_NOTIFYDSC;
$modversion['notification']['event'][3]['mail_template'] = 'global_newstory_notify';
$modversion['notification']['event'][3]['mail_subject']  = _MI_NEWS_GLOBAL_NEWSTORY_NOTIFYSBJ;

$modversion['notification']['event'][4]['name']          = 'approve';
$modversion['notification']['event'][4]['category']      = 'story';
$modversion['notification']['event'][4]['invisible']     = 1;
$modversion['notification']['event'][4]['title']         = _MI_NEWS_STORY_APPROVE_NOTIFY;
$modversion['notification']['event'][4]['caption']       = _MI_NEWS_STORY_APPROVE_NOTIFYCAP;
$modversion['notification']['event'][4]['description']   = _MI_NEWS_STORY_APPROVE_NOTIFYDSC;
$modversion['notification']['event'][4]['mail_template'] = 'story_approve_notify';
$modversion['notification']['event'][4]['mail_subject']  = _MI_NEWS_STORY_APPROVE_NOTIFYSBJ;

// Added by Lankford on 2007/3/23
$modversion['notification']['event'][5]['name']          = 'new_story';
$modversion['notification']['event'][5]['category']      = 'category';
$modversion['notification']['event'][5]['title']         = _MI_NEWS_CATEGORY_STORYPOSTED_NOTIFY;
$modversion['notification']['event'][5]['caption']       = _MI_NEWS_CATEGORY_STORYPOSTED_NOTIFYCAP;
$modversion['notification']['event'][5]['description']   = _MI_NEWS_CATEGORY_STORYPOSTED_NOTIFYDSC;
$modversion['notification']['event'][5]['mail_template'] = 'category_newstory_notify';
$modversion['notification']['event'][5]['mail_subject']  = _MI_NEWS_CATEGORY_STORYPOSTED_NOTIFYSBJ;
