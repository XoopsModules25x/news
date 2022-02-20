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
use XoopsModules\News\NewsTopic;

require_once __DIR__ . '/preloads/autoloader.php';
/** @var News\Helper $helper */
$helper = News\Helper::getInstance();
$helper->loadLanguage('common');

$moduleDirName = basename(__DIR__);
$moduleDirNameUpper = mb_strtoupper($moduleDirName);

$modversion['version']       = '1.72.0';
$modversion['module_status'] = 'Beta 5';
$modversion['release_date']  = '2022/02-20';
$modversion['name']          = _MI_NEWS_NAME;
$modversion['description']   = _MI_NEWS_DESC;
$modversion['credits']       = 'XOOPS Project, Christian, Pilou, Marco, <br>ALL the members of the Newbb Team, GIJOE, Zoullou, Mithrandir, <br>Setec Astronomy, Marcan, 5vision, Anne, Trabis, dhsoft, Mamba, Mage, Timgno';
$modversion['author']        = 'XOOPS Project Module Dev Team & Hervé Thouzard';
$modversion['nickname']      = 'hervet';
$modversion['help']          = 'page=help';
$modversion['license']       = 'GNU General Public License';
$modversion['license_url']   = 'https://www.gnu.org/licenses/gpl.html';
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
$modversion['author_website_url']  = 'https://xoops.org/';
$modversion['author_website_name'] = 'XOOPS';
$modversion['min_php']             = '7.2';
$modversion['min_xoops']           = '2.5.10';
$modversion['min_admin']           = '1.2';
$modversion['min_db']              = ['mysql' => '5.5'];

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

// Tables created by sql file (without prefix!)
//$modversion['tables'][0] = 'news_stories';
//$modversion['tables'][1] = 'news_topics';
//$modversion['tables'][2] = 'news_stories_files';
//$modversion['tables'][3] = 'news_stories_votedata';
$modversion['tables'] = [
    $moduleDirName . '_' . 'stories',
    $moduleDirName . '_' . 'topics',
    $moduleDirName . '_' . 'stories_files',
    $moduleDirName . '_' . 'stories_votedata',
];

$modversion['helpsection'] = [
    ['name' => _MI_NEWS_OVERVIEW, 'link' => 'page=help'],
    ['name' => _MI_NEWS_DISCLAIMER, 'link' => 'page=disclaimer'],
    ['name' => _MI_NEWS_LICENSE, 'link' => 'page=license'],
    ['name' => _MI_NEWS_SUPPORT, 'link' => 'page=support'],
];

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
/** @var \XoopsModuleHandler $moduleHandler */
$moduleHandler = xoops_getHandler('module');
$module        = $moduleHandler->getByDirname($modversion['dirname']);
if ($module) {
    global $xoopsUser;
    if (is_object($xoopsUser)) {
        $groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    }
    /** @var \XoopsGroupPermHandler $grouppermHandler */
    $grouppermHandler = xoops_getHandler('groupperm');
    if ($grouppermHandler->checkRight('news_submit', 0, $groups, $module->getVar('mid'))) {
        $cansubmit = 1;
    }
}

// ************
$i = 1;
global $xoopsDB, $xoopsUser, $xoopsConfig, $xoopsModule, $xoopsModuleConfig;
// We try to "win" some time
// 1)  Check to see it the module is the current module
if (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modversion['dirname']
    && $xoopsModule->getVar('isactive')) {
    // 2) If there's no topics to display as sub menus we can go on
    if (!isset($_SESSION['items_count']) || -1 == $_SESSION['items_count']) {
        $sql    = 'SELECT COUNT(*) AS cpt FROM ' . $xoopsDB->prefix('news_topics') . ' WHERE menu=1';
        $result = $xoopsDB->query($sql);
        [$count] = $xoopsDB->fetchRow($result);
        $_SESSION['items_count'] = $count;
    } else {
        $count = $_SESSION['items_count'];
    }
    if ($count > 0) {
        require_once XOOPS_ROOT_PATH . '/class/tree.php';
        //        require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
        $xt         = new NewsTopic();
        $allTopics  = $xt->getAllTopics(News\Utility::getModuleOption('restrictindex'));
        $topic_tree = new \XoopsObjectTree($allTopics, 'topic_id', 'topic_pid');
        $topics_arr = $topic_tree->getAllChild(0);
        if ($module) {
            foreach ($topics_arr as $onetopic) {
                if ($grouppermHandler->checkRight('news_view', $onetopic->topic_id(), $groups, $xoopsModule->getVar('mid'))
                    && $onetopic->menu()) {
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

//;
if (News\Utility::getModuleOption('newsbythisauthor')) {
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

/**
 * Select the number of news items to display on top page
 */
$modversion['config'][] = [
    'name'        => 'storyhome',
    'title'       => '_MI_STORYHOME',
    'description' => '_MI_STORYHOMEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 5,
    'options'     => ['5' => 5, '10' => 10, '15' => 15, '20' => 20, '25' => 25, '30' => 30],
];
/**
 * Format of the date to use in the module, if you don't specify anything then the default date's format will be used
 */
$modversion['config'][] = [
    'name'        => 'dateformat',
    'title'       => '_MI_NEWS_DATEFORMAT',
    'description' => '_MI_NEWS_DATEFORMAT_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '',
];
/**
 * Display a navigation's box on the pages ?
 * This navigation's box enable you to jump from one topic to another
 */
$modversion['config'][] = [
    'name'        => 'displaynav',
    'title'       => '_MI_DISPLAYNAV',
    'description' => '_MI_DISPLAYNAVDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];
/*
 $modversion['config'][] = [
 'name' =>  'anonpost',
 'title' =>  '_MI_ANONPOST',
 'description' =>  '',
 'formtype' =>  'yesno',
 'valuetype' =>  'int',
 'default' =>  0,
];
 */

/**
 * Auto approuve submited stories
 */
$modversion['config'][] = [
    'name'        => 'autoapprove',
    'title'       => '_MI_AUTOAPPROVE',
    'description' => '_MI_AUTOAPPROVEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Dispay layout, classic or by topics
 */
$modversion['config'][] = [
    'name'        => 'newsdisplay',
    'title'       => '_MI_NEWSDISPLAY',
    'description' => '_MI_NEWSDISPLAYDESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'Classic',
    'options'     => ['_MI_NEWSCLASSIC' => 'Classic', '_MI_NEWSBYTOPIC' => 'Bytopic'],
];

/**
 * How to display Author's name, username, full name or nothing ?
 */
$modversion['config'][] = [
    'name'        => 'displayname',
    'title'       => '_MI_NAMEDISPLAY',
    'description' => '_MI_ADISPLAYNAMEDSC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => [
    '_MI_DISPLAYNAME1' => 1,
    '_MI_DISPLAYNAME2' => 2,
        '_MI_DISPLAYNAME3' => 3,
    ],
];
/**
 * Number of columns to use to display news
 */
$modversion['config'][] = [
    'name'        => 'columnmode',
    'title'       => '_MI_COLUMNMODE',
    'description' => '_MI_COLUMNMODE_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 1,
    'options'     => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5],
];
/**
 * Number of news and topics to display in the module's admin part
 */
$modversion['config'][] = [
    'name'        => 'storycountadmin',
    'title'       => '_MI_STORYCOUNTADMIN',
    'description' => '_MI_STORYCOUNTADMIN_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 10,
    'options'     => [
    '5'  => 5,
    '10' => 10,
    '15' => 15,
    '20' => 20,
    '25' => 25,
    '30' => 30,
    '35' => 35,
        '40' => 40,
    ],
];

/**
 * Authorized groups to upload
 */
$modversion['config'][] = [
    'name'        => 'uploadgroups',
    'title'       => '_MI_UPLOADGROUPS',
    'description' => '_MI_UPLOADGROUPS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'default'     => 2,
    'options'     => [
    '_MI_UPLOAD_GROUP1' => 1,
    '_MI_UPLOAD_GROUP2' => 2,
        '_MI_UPLOAD_GROUP3' => 3,
    ],
];

/**
 * MAX Filesize Upload in kilo bytes
 */
$modversion['config'][] = [
    'name'        => 'maxuploadsize',
    'title'       => '_MI_UPLOADFILESIZE',
    'description' => '_MI_UPLOADFILESIZE_DESC',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 1048576,
];

/**
 * Display  Topic_title with news_title  ?
 * display Topic_title right before news_title in  news_item.tpl
 */
$modversion['config'][] = [
    'name'        => 'displaytopictitle',
    'title'       => '_MI_DISPLAYTOPIC_TITLE',
    'description' => '_MI_DISPLAYTOPIC_TITLEDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Restrict Topics on Index Page
 *
 * This is one of the mot important option in the module.
 * If you set it to No, then the users can see the introduction's text of each
 * story even if they don't have the right to see the topic attached to the news.
 * If you set it to Yes then you can only see what you have the right to see.
 * Many of the permissions are based on this option.
 */
$modversion['config'][] = [
    'name'        => 'restrictindex',
    'title'       => '_MI_RESTRICTINDEX',
    'description' => '_MI_RESTRICTINDEXDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Do you want to enable your visitors to see all the other articles
 * created by the author they are currently reading ?
 */
$modversion['config'][] = [
    'name'        => 'newsbythisauthor',
    'title'       => '_MI_NEWSBYTHISAUTHOR',
    'description' => '_MI_NEWSBYTHISAUTHORDSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * If you set this option to yes then you will see two links at the bottom
 * of each article. The first link will enable you to go to the previous
 * article and the other link will bring you to the next article
 */
$modversion['config'][] = [
    'name'        => 'showprevnextlink',
    'title'       => '_MI_NEWS_PREVNEX_LINK',
    'description' => '_MI_NEWS_PREVNEX_LINK_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Do you want to see a summary table at the bottom of each article ?
 */
$modversion['config'][] = [
    'name'        => 'showsummarytable',
    'title'       => '_MI_NEWS_SUMMARY_SHOW',
    'description' => '_MI_NEWS_SUMMARY_SHOW_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Do you enable author's to edit their posts ?
 */
$modversion['config'][] = [
    'name'        => 'authoredit',
    'title'       => '_MI_NEWS_AUTHOR_EDIT',
    'description' => '_MI_NEWS_AUTHOR_EDIT_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Do you want to enable your visitors to rate news ?
 */
$modversion['config'][] = [
    'name'        => 'ratenews',
    'title'       => '_MI_NEWS_RATE_NEWS',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * You can set RSS feeds per topic
 */
$modversion['config'][] = [
    'name'        => 'topicsrss',
    'title'       => '_MI_NEWS_TOPICS_RSS',
    'description' => '_MI_NEWS_TOPICS_RSS_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * If you set this option to yes then the approvers can type the keyword
 * and description's meta datas
 */
$modversion['config'][] = [
    'name'        => 'metadata',
    'title'       => '_MI_NEWS_META_DATA',
    'description' => '_MI_NEWS_META_DATA_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

// default admin editor
xoops_load('XoopsEditorHandler');
$editorHandler = \XoopsEditorHandler::getInstance();
$editorList    = array_flip($editorHandler->getList());

$modversion['config'][] = [
    'name'        => 'form_options',
    'title'       => '_MI_NEWS_FORM_OPTIONS',
    'description' => '_MI_NEWS_FORM_OPTIONS_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'text',
    'default'     => 'dhtml',
    'options'     => $editorList,
];

/**
 * If you set this option to Yes then the keywords entered in the
 * search will be highlighted in the articles.
 */
$modversion['config'][] = [
    'name'        => 'keywordshighlight',
    'title'       => '_MI_NEWS_KEYWORDS_HIGH',
    'description' => '_MI_NEWS_KEYWORDS_HIGH_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * If you have enabled the previous option then with this one
 * you can select the color to use to highlight words
 */
$modversion['config'][] = [
    'name'        => 'highlightcolor',
    'title'       => '_MI_NEWS_HIGH_COLOR',
    'description' => '_MI_NEWS_HIGH_COLOR_DES',
    'formtype'    => 'textbox',
    'valuetype'   => 'text',
    'default'     => '#FFFF80',
];

/**
 * Tooltips, or infotips are some small textes you can see when you
 * move your mouse over an article's title. This text contains the
 * first (x) characters of the story
 */
$modversion['config'][] = [
    'name'        => 'infotips',
    'title'       => '_MI_NEWS_INFOTIPS',
    'description' => '_MI_NEWS_INFOTIPS_DES',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => '0',
];

/**
 * This option is specific to Mozilla/Firefox and Opera
 * Both of them can display a toolbar wich contains buttons to
 * go from article to article. It can show other information too
 */
$modversion['config'][] = [
    'name'        => 'sitenavbar',
    'title'       => '_MI_NEWS_SITE_NAVBAR',
    'description' => '_MI_NEWS_SITE_NAVBAR_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * With this option you can select the skin (apparence) to use for the blocks containing tabs
 */
$modversion['config'][] = [
    'name'        => 'tabskin',
    'title'       => '_MI_NEWS_TABS_SKIN',
    'description' => '_MI_NEWS_TABS_SKIN_DESC',
    'formtype'    => 'select',
    'valuetype'   => 'int',
    'options'     => [
    _MI_NEWS_SKIN_1 => 1,
    _MI_NEWS_SKIN_2 => 2,
    _MI_NEWS_SKIN_3 => 3,
    _MI_NEWS_SKIN_4 => 4,
    _MI_NEWS_SKIN_5 => 5,
    _MI_NEWS_SKIN_6 => 6,
    _MI_NEWS_SKIN_7 => 7,
        _MI_NEWS_SKIN_8 => 8,
    ],
    'default'     => 6,
];

/**
 * Display a navigation's box on the pages ?
 * This navigation's box enable you to jump from one topic to another
 */
$modversion['config'][] = [
    'name'        => 'footNoteLinks',
    'title'       => '_MI_NEWS_FOOTNOTES',
    'description' => '',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Activate Dublin Core Metadata ?
 */
$modversion['config'][] = [
    'name'        => 'dublincore',
    'title'       => '_MI_NEWS_DUBLINCORE',
    'description' => '_MI_NEWS_DUBLINCORE_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Display a "Bookmark this article at these sites" block ?
 */
$modversion['config'][] = [
    'name'        => 'bookmarkme',
    'title'       => '_MI_NEWS_BOOKMARK_ME',
    'description' => '_MI_NEWS_BOOKMARK_ME_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Activate Firefox 2 microformats ? //obsolete, see here : http://wiki.mozilla.org/Microsummaries (cesagonchu)
 * $modversion['config'][] = [
 * 'name' =>  'firefox_microsummaries',
 * 'title' =>  '_MI_NEWS_FF_MICROFORMAT',
 * 'description' =>  '_MI_NEWS_FF_MICROFORMAT_DSC',
 * 'formtype' =>  'yesno',
 * 'valuetype' =>  'int',
 * 'default' =>  0,
 * ];
 */

/**
 * Advertisement
 */
$modversion['config'][] = [
    'name'        => 'advertisement',
    'title'       => '_MI_NEWS_ADVERTISEMENT',
    'description' => '_MI_NEWS_ADV_DESCR',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => '',
];

/**
 * Mime Types
 *
 * Default values : Web pictures (png, gif, jpeg), zip, pdf, gtar, tar, pdf
 */
$modversion['config'][] = [
    'name'        => 'mimetypes',
    'title'       => '_MI_NEWS_MIME_TYPES',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => "image/gif\nimage/jpeg\nimage/pjpeg\nimage/x-png\nimage/png\napplication/x-zip-compressed\napplication/zip\napplication/pdf\napplication/x-gtar\napplication/x-tar",
];

/**
 * Use enhanced page separator ?
 */
$modversion['config'][] = [
    'name'        => 'enhanced_pagenav',
    'title'       => '_MI_NEWS_ENHANCED_PAGENAV',
    'description' => '_MI_NEWS_ENHANCED_PAGENAV_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Use the TAGS system ?
 */
$modversion['config'][] = [
    'name'        => 'tags',
    'title'       => '_MI_NEWS_TAGS',
    'description' => '_MI_NEWS_TAGS_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Introduction text to show on the submit page
 */
$modversion['config'][] = [
    'name'        => 'submitintromsg',
    'title'       => '_MI_NEWS_INTRO_TEXT',
    'description' => '',
    'formtype'    => 'textarea',
    'valuetype'   => 'text',
    'default'     => '',
];

/**
 * Max width
 */
$modversion['config'][] = [
    'name'        => 'maxwidth',
    'title'       => '_MI_NEWS_IMAGE_MAX_WIDTH',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 640,
];

/**
 * Max height
 */
$modversion['config'][] = [
    'name'        => 'maxheight',
    'title'       => '_MI_NEWS_IMAGE_MAX_HEIGHT',
    'description' => '',
    'formtype'    => 'textbox',
    'valuetype'   => 'int',
    'default'     => 480,
];

/**
 * Display a "share" items ?
 */
$modversion['config'][] = [
    'name'        => 'share',
    'title'       => '_MI_NEWS_SHARE_ME',
    'description' => '_MI_NEWS_SHARE_ME_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Display Print and Email icons in each article ?
 */
$modversion['config'][] = [
    'name'        => 'showicons',
    'title'       => '_MI_NEWS_SHOWICONS',
    'description' => '_MI_NEWS_SHOWICONS_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

/**
 * Display PDF icon in each article ?
 */
$modversion['config'][] = [
    'name'        => 'show_pdficon',
    'title'       => '_MI_NEWS_SHOWICONS_PDF',
    'description' => '_MI_NEWS_SHOWICONS_PDF_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Allow Facebook Comments?
 */
$modversion['config'][] = [
    'name'        => 'fbcomments',
    'title'       => '_MI_NEWS_FACEBOOKCOMMENTS',
    'description' => '_MI_NEWS_FACEBOOKCOMMENTS_DSC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 0,
];

/**
 * Make Sample button visible?
 */
$modversion['config'][] = [
    'name'        => 'displaySampleButton',
    'title'       => '_MI_NEWS_SHOW_SAMPLE_BUTTON',
    'description' => '_MI_NEWS_SHOW_SAMPLE_BUTTON_DESC',
    'formtype'    => 'yesno',
    'valuetype'   => 'int',
    'default'     => 1,
];

// Notification
$modversion['hasNotification']             = 1;
$modversion['notification']['lookup_file'] = 'include/notification.inc.php';
$modversion['notification']['lookup_func'] = 'news_notify_iteminfo';

$modversion['notification']['category'][1]['name']           = 'global';
$modversion['notification']['category'][1]['title']          = _MI_NEWS_GLOBAL_NOTIFY;
$modversion['notification']['category'][1]['description']    = _MI_NEWS_GLOBAL_NOTIFYDSC;
$modversion['notification']['category'][1]['subscribe_from'] = ['index.php', 'article.php'];

$modversion['notification']['category'][2]['name']           = 'story';
$modversion['notification']['category'][2]['title']          = _MI_NEWS_STORY_NOTIFY;
$modversion['notification']['category'][2]['description']    = _MI_NEWS_STORY_NOTIFYDSC;
$modversion['notification']['category'][2]['subscribe_from'] = ['article.php'];
$modversion['notification']['category'][2]['item_name']      = 'storyid';
$modversion['notification']['category'][2]['allow_bookmark'] = 1;

// Added by Lankford on 2007/3/23
$modversion['notification']['category'][3]['name']           = 'category';
$modversion['notification']['category'][3]['title']          = _MI_NEWS_CATEGORY_NOTIFY;
$modversion['notification']['category'][3]['description']    = _MI_NEWS_CATEGORY_NOTIFYDSC;
$modversion['notification']['category'][3]['subscribe_from'] = ['index.php', 'article.php'];
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
