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

// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';

/**
 * Notes about the spotlight :
 * If you have restricted topics on index page (in fact if the program must completly respect the permissions) and if
 * the news you have selected to be viewed in the spotlight can't be viewed by someone then the spotlight is not visible !
 * This is available in the classical and in the tabbed view.
 * But if you have uncheck the option "Restrict topics on index page", then the news will be visible but users without
 * permissions will be rejected when they will try to read news content.
 *
 * Also, if you have selected a tabbed view and wanted to use the Spotlight but did not choosed a story, then the block
 * will switch to the "most recent news" mode (the visible news will be searched according to the permissions)
 * @param $options
 * @return array|string
 */
function b_news_top_show($options)
{
    global $xoopsConfig;
    require_once XOOPS_ROOT_PATH . '/modules/news/class/utility.php';
    $myts        = MyTextSanitizer::getInstance();
    $block       = [];
    $displayname = NewsUtility::getModuleOption('displayname');
    $tabskin     = NewsUtility::getModuleOption('tabskin');

    if (file_exists(XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/main.php')) {
        require_once XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/main.php';
    } else {
        require_once XOOPS_ROOT_PATH . '/modules/news/language/english/main.php';
    }

    $block['displayview'] = $options[8];
    $block['tabskin']     = $tabskin;
    $block['imagesurl']   = XOOPS_URL . '/modules/news/assets/images/';

    $restricted = NewsUtility::getModuleOption('restrictindex');
    $dateformat = NewsUtility::getModuleOption('dateformat');
    $infotips   = NewsUtility::getModuleOption('infotips');
    $newsrating = NewsUtility::getModuleOption('ratenews');
    if ('' == $dateformat) {
        $dateformat = 's';
    }

    $perm_verified = false;
    $news_visible  = true;
    // Is the spotlight visible ?
    if (1 == $options[4] && $restricted && 0 == $options[5]) {
        $perm_verified   = true;
        $permittedtopics = NewsUtility::getMyItemIds();
        $permstory       = new NewsStory($options[6]);
        if (!in_array($permstory->topicid(), $permittedtopics)) {
            $usespotlight = false;
            $news_visible = false;
            $topicstitles = [];
        }
        0 == $options[4];
    }
    // Try to see what tabs are visibles (if we are in restricted view of course)
    if (2 == $options[8] && $restricted && 0 != $options[14]) {
        $topics2         = [];
        $permittedtopics = NewsUtility::getMyItemIds();
        $topics          = array_slice($options, 14);
        foreach ($topics as $onetopic) {
            if (in_array($onetopic, $permittedtopics)) {
                $topics2[] = $onetopic;
            }
        }
        $before  = array_slice($options, 0, 14);
        $options = array_merge($before, $topics2);
    }

    if (2 == $options[8]) { // Tabbed view ********************************************************************************************
        $defcolors[1] = ['#F90', '#FFFFFF', '#F90', '#C60', '#999']; // Bar Style
        $defcolors[2] = ['#F90', '#FFFFFF', '#F90', '#AAA', '#666']; // Beveled
        $defcolors[3] = ['#F90', '#FFFFFF', '', '#789', '#789']; // Classic
        $defcolors[4] = ['#F90', '#FFFFFF', '', '', '']; // Folders
        $defcolors[5] = ['#F90', '#FFFFFF', '#CCC', 'inherit', '#999']; // MacOs
        $defcolors[6] = ['#F90', '#FFFFFF', '#FFF', '#DDD', '#999']; // Plain
        $defcolors[7] = ['#F90', '#FFFFFF', '', '', '']; // Rounded
        $defcolors[8] = ['#F90', '#FFFFFF', '#F90', '#930', '#C60']; // ZDnet

        $myurl = $_SERVER['PHP_SELF'];
        if ('/' === substr($myurl, strlen($myurl) - 1, 1)) {
            $myurl .= 'index.php';
        }
        $myurl .= '?';

        foreach ($_GET as $key => $value) {
            if ('NewsTab' !== $key) {
                $myurl .= $key . '=' . $value . '&';
            }
        }
        $block['url'] = $myurl;

        $tabscount    = 0;
        $usespotlight = false;

        if (isset($_GET['NewsTab'])) {
            $_SESSION['NewsTab'] = (int)$_GET['NewsTab'];
            $currenttab          = (int)$_GET['NewsTab'];
        } elseif (isset($_SESSION['NewsTab'])) {
            $currenttab = (int)$_SESSION['NewsTab'];
        } else {
            $currenttab = 0;
        }

        $tmpstory     = new NewsStory();
        $topic        = new NewsTopic();
        $topicstitles = [];
        if (1 == $options[4]) { // Spotlight enabled
            $topicstitles[0] = _MB_NEWS_SPOTLIGHT_TITLE;
            ++$tabscount;
            $usespotlight = true;
        }

        if (0 == $options[5] && $restricted) { // Use a specific news and we are in restricted mode
            if (!$perm_verified) {
                $permittedtopics = NewsUtility::getMyItemIds();
                $permstory       = new NewsStory($options[6]);
                if (!in_array($permstory->topicid(), $permittedtopics)) {
                    $usespotlight = false;
                    $topicstitles = [];
                }
                //unset($permstory);
            } else {
                if (!$news_visible) {
                    $usespotlight = false;
                    $topicstitles = [];
                }
            }
        }

        $block['use_spotlight'] = $usespotlight;

        if (isset($options[14]) && 0 != $options[14]) { // Topic to use
            $topics       = array_slice($options, 14);
            $tabscount    += count($topics);
            $topicstitles = $topic->getTopicTitleFromId($topics, $topicstitles);
        }
        $tabs = [];
        if ($usespotlight) {
            $tabs[] = ['id' => 0, 'title' => _MB_NEWS_SPOTLIGHT_TITLE];
        }
        if (count($topics) > 0) {
            foreach ($topics as $onetopic) {
                if (isset($topicstitles[$onetopic])) {
                    $tabs[] = [
                        'id'      => $onetopic,
                        'title'   => $topicstitles[$onetopic]['title'],
                        'picture' => $topicstitles[$onetopic]['picture']
                    ];
                }
            }
        }
        $block['tabs']                 = $tabs;
        $block['current_is_spotlight'] = false;
        $block['current_tab']          = $currenttab;
        $block['use_rating']           = $newsrating;

        if (0 == $currenttab && $usespotlight) { // Spotlight or not ?
            $block['current_is_spotlight'] = true;
            if (0 == $options[5]
                && 0 == $options[6]) { // If the story to use was no selected then we switch to the "recent news" mode.
                $options[5] = 1;
            }

            if (0 == $options[5]) { // Use a specific news
                if (!isset($permstory)) {
                    $tmpstory = new NewsStory($options[6]);
                } else {
                    $tmpstory = $permstory;
                }
            } else { // Use the most recent news
                $stories = [];
                $stories = NewsStory::getAllPublished(1, 0, $restricted, 0, 1, true, $options[0]);
                if (count($stories) > 0) {
                    $firststory = $stories[0];
                    $tmpstory = new NewsStory($firststory->storyid());
                } else {
                    $block['use_spotlight'] = false;
                }
            }
            $spotlight          = [];
            $spotlight['title'] = $tmpstory->title();
            if ('' !== $options[7]) {
                $spotlight['image'] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $tmpstory->storyid(), $myts->displayTarea($options[7], $tmpstory->nohtml));
            }
            $spotlight['text'] = $tmpstory->hometext();

            // Added 16 february 2007 *****************************************
            $story_user = null;
            $story_user = new XoopsUser($tmpstory->uid());
            if (is_object($story_user)) {
                $spotlight['avatar'] = XOOPS_UPLOAD_URL . '/' . $story_user->getVar('user_avatar');
            }
            // ****************************************************************
            $spotlight['id']     = $tmpstory->storyid();
            $spotlight['date']   = formatTimestamp($tmpstory->published(), $dateformat);
            $spotlight['hits']   = $tmpstory->counter();
            $spotlight['rating'] = number_format($tmpstory->rating(), 2);
            $spotlight['votes']  = $tmpstory->votes();
            if (strlen(xoops_trim($tmpstory->bodytext())) > 0) {
                $spotlight['read_more'] = true;
            } else {
                $spotlight['read_more'] = false;
            }

            $spotlight['readmore']        = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $tmpstory->storyid(), _MB_READMORE);
            $spotlight['title_with_link'] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $tmpstory->storyid(), $tmpstory->title());
            if (1 == $tmpstory->votes()) {
                $spotlight['number_votes'] = _NW_ONEVOTE;
            } else {
                $spotlight['number_votes'] = sprintf(_NW_NUMVOTES, $tmpstory->votes());
            }

            $spotlight['votes_with_text'] = sprintf(_NW_NUMVOTES, $tmpstory->votes());
            $spotlight['topicid']         = $tmpstory->topicid();
            $spotlight['topic_title']     = $tmpstory->topic_title();
            // Added, topic's image and description
            $spotlight['topic_image']       = XOOPS_URL . '/modules/news/assets/images/topics/' . $tmpstory->topic_imgurl();
            $spotlight['topic_description'] = $myts->displayTarea($tmpstory->topic_description, 1);

            if (3 != $displayname) {
                $spotlight['author']           = sprintf('%s %s', _POSTEDBY, $tmpstory->uname());
                $spotlight['author_with_link'] = sprintf("%s <a href='%s'>%s</a>", _POSTEDBY, XOOPS_URL . '/userinfo.php?uid=' . $tmpstory->uid(), $tmpstory->uname());
            } else {
                $spotlight['author']           = '';
                $spotlight['author_with_link'] = '';
            }
            $spotlight['author_id'] = $tmpstory->uid();

            // Create the summary table under the spotlight text
            if (isset($options[14]) && 0 == $options[14]) { // Use all topics
                $stories = NewsStory::getAllPublished($options[1], 0, $restricted, 0, 1, true, $options[0]);
            } else { // Use some topics
                $topics  = array_slice($options, 14);
                $stories = NewsStory::getAllPublished($options[1], 0, $restricted, $topics, 1, true, $options[0]);
            }
            if (count($stories) > 0) {
                foreach ($stories as $key => $story) {
                    $news  = [];
                    $title = $story->title();
                    if (strlen($title) > $options[2]) {
                        $title = xoops_substr($title, 0, $options[2] + 3);
                    }
                    $news['title']       = $title;
                    $news['id']          = $story->storyid();
                    $news['date']        = formatTimestamp($story->published(), $dateformat);
                    $news['hits']        = $story->counter();
                    $news['rating']      = number_format($story->rating(), 2);
                    $news['votes']       = $story->votes();
                    $news['topicid']     = $story->topicid();
                    $news['topic_title'] = $story->topic_title();
                    $news['topic_color'] = '#' . $myts->displayTarea($story->topic_color);
                    $news['picture']     = XOOPS_URL . '/uploads/news/image/' . $story->picture();
                    $news['pictureinfo'] = $story->pictureinfo();
                    if (3 != $displayname) {
                        $news['author'] = sprintf('%s %s', _POSTEDBY, $story->uname());
                    } else {
                        $news['author'] = '';
                    }
                    if ($options[3] > 0) {
                        $html           = 1 == $story->nohtml() ? 0 : 1;
                        $news['teaser'] = NewsUtility::truncateTagSafe($myts->displayTarea($story->hometext(), $html), $options[3] + 3);
                    } else {
                        $news['teaser'] = '';
                    }
                    if ($infotips > 0) {
                        $news['infotips'] = ' title="' . NewsUtility::makeInfotips($story->hometext()) . '"';
                    } else {
                        $news['infotips'] = '';
                    }

                    $news['title_with_link'] = sprintf("<a href='%s'%s>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(), $news['infotips'], $title);
                    $spotlight['news'][]     = $news;
                }
            }

            $block['spotlight'] = $spotlight;
        } else {
            if ($tabscount > 0) {
                $topics   = array_slice($options, 14);
                $thetopic = $currenttab;
                $stories  = NewsStory::getAllPublished($options[1], 0, $restricted, $thetopic, 1, true, $options[0]);

                $topic->getTopic($thetopic);
                // Added, topic's image and description
                $block['topic_image']       = XOOPS_URL . '/modules/news/assets/images/topics/' . $topic->topic_imgurl();
                $block['topic_description'] = $topic->topic_description();

                $smallheader   = [];
                $stats         = $topic->getTopicMiniStats($thetopic);
                $smallheader[] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/index.php?storytopic=' . $thetopic, _MB_READMORE);
                $smallheader[] = sprintf('%u %s', $stats['count'], _NW_ARTICLES);
                $smallheader[] = sprintf('%u %s', $stats['reads'], _READS);
                if (count($stories) > 0) {
                    foreach ($stories as $key => $story) {
                        $news  = [];
                        $title = $story->title();
                        if (strlen($title) > $options[2]) {
                            $title = NewsUtility::truncateTagSafe($title, $options[2] + 3);
                        }
                        if ('' !== $options[7]) {
                            $news['image'] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(), $myts->displayTarea($options[7], $story->nohtml));
                        }
                        if ($options[3] > 0) {
                            $html         = 1 == $story->nohtml() ? 0 : 1;
                            $news['text'] = NewsUtility::truncateTagSafe($myts->displayTarea($story->hometext(), $html), $options[3] + 3);
                        } else {
                            $news['text'] = '';
                        }

                        if (1 == $story->votes()) {
                            $news['number_votes'] = _NW_ONEVOTE;
                        } else {
                            $news['number_votes'] = sprintf(_NW_NUMVOTES, $story->votes());
                        }
                        if ($infotips > 0) {
                            $news['infotips'] = ' title="' . NewsUtility::makeInfotips($story->hometext()) . '"';
                        } else {
                            $news['infotips'] = '';
                        }
                        $news['title']       = sprintf("<a href='%s' %s>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(), $news['infotips'], $title);
                        $news['id']          = $story->storyid();
                        $news['date']        = formatTimestamp($story->published(), $dateformat);
                        $news['hits']        = $story->counter();
                        $news['rating']      = number_format($story->rating(), 2);
                        $news['votes']       = $story->votes();
                        $news['topicid']     = $story->topicid();
                        $news['topic_title'] = $story->topic_title();
                        $news['topic_color'] = '#' . $myts->displayTarea($story->topic_color);
                        $news['picture']     = XOOPS_URL . '/uploads/news/image/' . $story->picture();
                        $news['pictureinfo'] = $story->pictureinfo();

                        if (3 != $displayname) {
                            $news['author'] = sprintf('%s %s', _POSTEDBY, $story->uname());
                        } else {
                            $news['author'] = '';
                        }
                        $news['title_with_link'] = sprintf("<a href='%s'%s>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(), $news['infotips'], $title);
                        $block['news'][]         = $news;
                    }
                    $block['smallheader'] = $smallheader;
                }
            }
        }
        $block['lang_on']    = _ON; // on
        $block['lang_reads'] = _READS; // reads
        // Default values
        $block['color1'] = $defcolors[$tabskin][0];
        $block['color2'] = $defcolors[$tabskin][1];
        $block['color3'] = $defcolors[$tabskin][2];
        $block['color4'] = $defcolors[$tabskin][3];
        $block['color5'] = $defcolors[$tabskin][4];

        if ('' !== xoops_trim($options[9])) {
            $block['color1'] = $options[9];
        }
        if ('' !== xoops_trim($options[10])) {
            $block['color2'] = $options[10];
        }
        if ('' !== xoops_trim($options[11])) {
            $block['color3'] = $options[11];
        }
        if ('' !== xoops_trim($options[12])) {
            $block['color4'] = $options[12];
        }
        if ('' !== xoops_trim($options[13])) {
            $block['color5'] = $options[13];
        }
    } else { // ************************ Classical view **************************************************************************************************************
        $tmpstory = new NewsStory;
        if (isset($options[14]) && 0 == $options[14]) {
            $stories = NewsStory::getAllPublished($options[1], 0, $restricted, 0, 1, true, $options[0]);
        } else {
            $topics  = array_slice($options, 14);
            $stories = NewsStory::getAllPublished($options[1], 0, $restricted, $topics, 1, true, $options[0]);
        }

        if (!count($stories)) {
            return '';
        }
        $topic = new NewsTopic();

        foreach ($stories as $key => $story) {
            $news  = [];
            $title = $story->title();
            if (strlen($title) > $options[2]) {
                $title = xoops_substr($title, 0, $options[2] + 3);
            }

            //if spotlight is enabled and this is either the first article or the selected one
            if ((0 == $options[5]) && (1 == $options[4])
                && (($options[6] > 0 && $options[6] == $story->storyid())
                    || (0 == $options[6] && 0 == $key))) {
                $spotlight = [];
                $visible   = true;
                if ($restricted) {
                    $permittedtopics = NewsUtility::getMyItemIds();
                    if (!in_array($story->topicid(), $permittedtopics)) {
                        $visible = false;
                    }
                }

                if ($visible) {
                    $spotlight['title'] = $title;
                    if ('' !== $options[7]) {
                        $spotlight['image'] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $story->storyid(), $myts->displayTarea($options[7], $story->nohtml));
                    }
                    // Added 16 february 2007 *****************************************
                    $story_user = null;
                    $story_user = new XoopsUser($story->uid());
                    if (is_object($story_user)) {
                        $spotlight['avatar'] = XOOPS_UPLOAD_URL . '/' . $story_user->getVar('user_avatar');
                    }
                    // ****************************************************************
                    $spotlight['text']        = $story->hometext();
                    $spotlight['id']          = $story->storyid();
                    $spotlight['date']        = formatTimestamp($story->published(), $dateformat);
                    $spotlight['hits']        = $story->counter();
                    $spotlight['rating']      = $story->rating();
                    $spotlight['votes']       = $story->votes();
                    $spotlight['topicid']     = $story->topicid();
                    $spotlight['topic_title'] = $story->topic_title();
                    $spotlight['topic_color'] = '#' . $myts->displayTarea($story->topic_color);
                    // Added, topic's image and description
                    $spotlight['topic_image']       = XOOPS_URL . '/modules/news/assets/images/topics/' . $story->topic_imgurl();
                    $spotlight['topic_description'] = $myts->displayTarea($story->topic_description, 1);
                    if (strlen(xoops_trim($story->bodytext())) > 0) {
                        $spotlight['read_more'] = true;
                    } else {
                        $spotlight['read_more'] = false;
                    }

                    if (3 != $displayname) {
                        $spotlight['author'] = sprintf('%s %s', _POSTEDBY, $story->uname());
                    } else {
                        $spotlight['author'] = '';
                    }
                }
                $block['spotlight'] = $spotlight;
            } else {
                $news['title']       = $title;
                $news['id']          = $story->storyid();
                $news['date']        = formatTimestamp($story->published(), $dateformat);
                $news['hits']        = $story->counter();
                $news['rating']      = $story->rating();
                $news['votes']       = $story->votes();
                $news['topicid']     = $story->topicid();
                $news['topic_title'] = $story->topic_title();
                $news['topic_color'] = '#' . $myts->displayTarea($story->topic_color);
                $news['picture']     = XOOPS_URL . '/uploads/news/image/' . $story->picture();
                $news['pictureinfo'] = $story->pictureinfo();

                if (3 != $displayname) {
                    $news['author'] = sprintf('%s %s', _POSTEDBY, $story->uname());
                } else {
                    $news['author'] = '';
                }
                if ($options[3] > 0) {
                    $html             = 1 == $story->nohtml() ? 0 : 1;
                    $news['teaser']   = NewsUtility::truncateTagSafe($myts->displayTarea($story->hometext(), $html), $options[3] + 3);
                    $news['infotips'] = '';
                } else {
                    $news['teaser'] = '';
                    if ($infotips > 0) {
                        $news['infotips'] = ' title="' . NewsUtility::makeInfotips($story->hometext()) . '"';
                    } else {
                        $news['infotips'] = '';
                    }
                }
                $block['stories'][] = $news;
            }
        }

        // If spotlight article was not in the fetched stories
        if (!isset($spotlight) && $options[4]) {
            $block['use_spotlight'] = true;
            $visible                = true;
            if (0 == $options[5] && $restricted) { // Use a specific news and we are in restricted mode
                $permittedtopics = NewsUtility::getMyItemIds();
                $permstory       = new NewsStory($options[6]);
                if (!in_array($permstory->topicid(), $permittedtopics)) {
                    $visible = false;
                }
                unset($permstory);
            }

            if (0 == $options[5]) { // Use a specific news
                if ($visible) {
                    $spotlightArticle = new NewsStory($options[6]);
                } else {
                    $block['use_spotlight'] = false;
                }
            } else { // Use the most recent news
                $stories = [];
                $stories = NewsStory::getAllPublished(1, 0, $restricted, 0, 1, true, $options[0]);
                if (count($stories) > 0) {
                    $firststory       = $stories[0];
                    $spotlightArticle = new NewsStory($firststory->storyid());
                } else {
                    $block['use_spotlight'] = false;
                }
            }
            if (true === $block['use_spotlight']) {
                $spotlight          = [];
                $spotlight['title'] = xoops_substr($spotlightArticle->title(), 0, $options[2] - 1);
                if ('' !== $options[7]) {
                    $spotlight['image'] = sprintf("<a href='%s'>%s</a>", XOOPS_URL . '/modules/news/article.php?storyid=' . $spotlightArticle->storyid(), $myts->displayTarea($options[7], $spotlightArticle->nohtml));
                }
                // Added 16 february 2007 *****************************************
                $story_user = null;
                $story_user = new XoopsUser($spotlightArticle->uid());
                if (is_object($story_user)) {
                    $spotlight['avatar'] = XOOPS_UPLOAD_URL . '/' . $story_user->getVar('user_avatar');
                }
                // ****************************************************************
                $spotlight['topicid']     = $spotlightArticle->topicid();
                $spotlight['topic_title'] = $spotlightArticle->topic_title();
                $spotlight['topic_color'] = '#' . $myts->displayTarea($spotlightArticle->topic_color);
                $spotlight['text']        = $spotlightArticle->hometext();
                $spotlight['id']          = $spotlightArticle->storyid();
                $spotlight['date']        = formatTimestamp($spotlightArticle->published(), $dateformat);
                $spotlight['hits']        = $spotlightArticle->counter();
                $spotlight['rating']      = $spotlightArticle->rating();
                $spotlight['votes']       = $spotlightArticle->votes();
                // Added, topic's image and description
                $spotlight['topic_image']       = XOOPS_URL . '/modules/news/assets/images/topics/' . $spotlightArticle->topic_imgurl();
                $spotlight['topic_description'] = $myts->displayTarea($spotlightArticle->topic_description, 1);
                if (3 != $displayname) {
                    $spotlight['author'] = sprintf('%s %s', _POSTEDBY, $spotlightArticle->uname());
                } else {
                    $spotlight['author'] = '';
                }
                if (strlen(xoops_trim($spotlightArticle->bodytext())) > 0) {
                    $spotlight['read_more'] = true;
                } else {
                    $spotlight['read_more'] = false;
                }
                $block['spotlight'] = $spotlight;
            }
        }
    }
    if (isset($permstory)) {
        unset($permstory);
    }
    $block['lang_read_more']      = $myts->htmlSpecialChars(_MB_READMORE); // Read More...
    $block['lang_orderby']        = $myts->htmlSpecialChars(_MB_NEWS_ORDER); // "Order By"
    $block['lang_orderby_date']   = $myts->htmlSpecialChars(_MB_NEWS_DATE); // Published date
    $block['lang_orderby_hits']   = $myts->htmlSpecialChars(_MB_NEWS_HITS); // Number of Hits
    $block['lang_orderby_rating'] = $myts->htmlSpecialChars(_MB_NEWS_RATE); // Rating
    $block['sort']                = $options[0]; // "published" or "counter" or "rating"

    return $block;
}

/**
 * Function used to edit the block
 * @param $options
 * @return string
 */
function b_news_top_edit($options)
{
    global $xoopsDB;
    $tmpstory = new NewsStory;
    $form     = _MB_NEWS_ORDER . "&nbsp;<select name='options[]'>";
    $form     .= "<option value='published'";
    if ('published' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ('counter' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_HITS . '</option>';
    $form .= "<option value='rating'";
    if ('rating' === $options[0]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_RATE . '</option>';
    $form .= "</select>\n";

    $form .= '&nbsp;' . _MB_NEWS_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_NEWS_ARTCLS;
    $form .= '&nbsp;<br><br>' . _MB_NEWS_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_NEWS_LENGTH . '<br><br>';

    $form .= _MB_NEWS_TEASER . " <input type='text' name='options[]' value='" . $options[3] . "'>" . _MB_NEWS_LENGTH;
    $form .= '<br><br>';

    $form .= _MB_NEWS_SPOTLIGHT . " <input type='radio' name='options[]' value='1'";
    if (1 == $options[4]) {
        $form .= ' checked';
    }
    $form .= '>' . _YES;
    $form .= "<input type='radio' name='options[]' value='0'";
    if (0 == $options[4]) {
        $form .= ' checked';
    }
    $form .= '>' . _NO . '<br><br>';

    $form .= _MB_NEWS_WHAT_PUBLISH . " <select name='options[]'><option value='1'";
    if (1 == $options[5]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_RECENT_NEWS;
    $form .= "</option><option value='0'";
    if (0 == $options[5]) {
        $form .= ' selected';
    }
    $form .= '>' . _MB_NEWS_RECENT_SPECIFIC . '</option></select>';

    $form     .= '<br><br>' . _MB_NEWS_SPOTLIGHT_ARTICLE . '<br>';
    $articles = NewsStory::getAllPublished(200, 0, false, 0, 0, false); // I have limited the listbox to the last 200 articles
    $form     .= "<select name ='options[]'>";
    $form     .= "<option value='0'>" . _MB_NEWS_FIRST . '</option>';
    foreach ($articles as $storyid => $storytitle) {
        $sel = '';
        if ($options[6] == $storyid) {
            $sel = ' selected';
        }
        $form .= "<option value='$storyid'$sel>" . $storytitle . '</option>';
    }
    $form .= '</select><br><br>';

    $form .= _MB_NEWS_IMAGE . "&nbsp;<input type='text' id='spotlightimage' name='options[]' value='" . $options[7] . "' size='50'>";
    $form .= "&nbsp;<img align='middle' onmouseover='style.cursor=\"hand\"' onclick='javascript:openWithSelfMain(\"" . XOOPS_URL . "/imagemanager.php?target=spotlightimage\",\"imgmanager\",400,430);' src='" . XOOPS_URL . "/images/image.gif' alt='image' title='image'>";
    $form .= '<br><br>' . _MB_NEWS_DISP . "&nbsp;<select name='options[]'><option value='1' ";
    if (1 == $options[8]) {
        $form .= 'selected';
    }
    $form .= '>' . _MB_NEWS_VIEW_TYPE1 . "</option><option value='2' ";
    if (2 == $options[8]) {
        $form .= 'selected';
    }
    $form .= '>' . _MB_NEWS_VIEW_TYPE2 . '</option></select><br><br>';

    $form .= "<table border=0>\n";
    $form .= "<tr><td colspan='2' align='center'><u>" . _MB_NEWS_DEFAULT_COLORS . '</u></td></tr>';
    $form .= '<tr><td>' . _MB_NEWS_TAB_COLOR1 . "</td><td><input type='text' name='options[]' value='" . $options[9] . "' size=7></td></tr>";
    $form .= '<tr><td>' . _MB_NEWS_TAB_COLOR2 . "</td><td><input type='text' name='options[]' value='" . $options[10] . "' size=7></td></tr>";
    $form .= '<tr><td>' . _MB_NEWS_TAB_COLOR3 . "</td><td><input type='text' name='options[]' value='" . $options[11] . "' size=7></td></tr>";
    $form .= '<tr><td>' . _MB_NEWS_TAB_COLOR4 . "</td><td><input type='text' name='options[]' value='" . $options[12] . "' size=7></td></tr>";
    $form .= '<tr><td>' . _MB_NEWS_TAB_COLOR5 . "</td><td><input type='text' name='options[]' value='" . $options[13] . "' size=7></td></tr>";
    $form .= "</table>\n";

    $form .= '<br><br>' . _MB_SPOTLIGHT_TOPIC . "<br><select name='options[]' multiple='multiple'>";
    require_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
    $topics_arr = [];
    require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopstree.php';
    $xt         = new MyXoopsTree($xoopsDB->prefix('news_topics'), 'topic_id', 'topic_pid');
    $topics_arr = $xt->getChildTreeArray(0, 'topic_title');
    $size       = count($options);
    foreach ($topics_arr as $onetopic) {
        $sel = '';
        if (0 != $onetopic['topic_pid']) {
            $onetopic['prefix'] = str_replace('.', '-', $onetopic['prefix']) . '&nbsp;';
        } else {
            $onetopic['prefix'] = str_replace('.', '', $onetopic['prefix']);
        }
        for ($i = 14; $i < $size; ++$i) {
            if ($options[$i] == $onetopic['topic_id']) {
                $sel = ' selected';
            }
        }
        $form .= "<option value='" . $onetopic['topic_id'] . "'$sel>" . $onetopic['prefix'] . $onetopic['topic_title'] . '</option>';
    }
    $form .= '</select><br>';

    return $form;
}

/**
 * @param $options
 */
function b_news_top_onthefly($options)
{
    $options = explode('|', $options);
    $block   = &b_news_top_show($options);

    $tpl = new XoopsTpl();
    $tpl->assign('block', $block);
    $tpl->display('db:news_block_top.tpl');
}
