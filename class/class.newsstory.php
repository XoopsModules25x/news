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
// ------------------------------------------------------------------------- //
// defined('XOOPS_ROOT_PATH') || exit('XOOPS root path not defined');

include_once XOOPS_ROOT_PATH . '/modules/news/class/xoopsstory.php';
include_once XOOPS_ROOT_PATH . '/include/comment_constants.php';
include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';

/**
 * Class NewsStory
 */
class NewsStory extends MyXoopsStory
{
    public $newstopic; // XoopsTopic object
    public $rating; // News rating
    public $votes; // Number of votes
    public $description; // META, desciption
    public $keywords; // META, keywords
    public $picture;
    public $topic_imgurl;
    public $topic_title;
    public $subtitle;
    public $pictureinfo;

    /**
     * Constructor
     * @param int $storyid
     */
    public function __construct($storyid = -1)
    {
        $this->db          = XoopsDatabaseFactory::getDatabaseConnection();
        $this->table       = $this->db->prefix('news_stories');
        $this->topicstable = $this->db->prefix('news_topics');
        if (is_array($storyid)) {
            $this->makeStory($storyid);
        } elseif ($storyid != -1) {
            $this->getStory((int)$storyid);
        }
    }

    /**
     * Returns the number of stories published before a date
     * @param         $timestamp
     * @param         $expired
     * @param  string $topicslist
     * @return mixed
     */
    public function GetCountStoriesPublishedBefore($timestamp, $expired, $topicslist = '')
    {
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = 'SELECT count(*) as cpt FROM ' . $db->prefix('news_stories') . ' WHERE published <=' . $timestamp;
        if ($expired) {
            $sql .= ' AND (expired>0 AND expired<=' . time() . ')';
        }
        if (strlen(trim($topicslist)) > 0) {
            $sql .= ' AND topicid IN (' . $topicslist . ')';
        }
        $result = $db->query($sql);
        list($count) = $db->fetchRow($result);

        return $count;
    }

    /**
     * Load the specified story from the database
     * @param $storyid
     */
    public function getStory($storyid)
    {
        $sql   = 'SELECT s.*, t.* FROM '
                 . $this->table
                 . ' s, '
                 . $this->db->prefix('news_topics')
                 . ' t WHERE (storyid='
                 . (int)$storyid
                 . ') AND (s.topicid=t.topic_id)';
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeStory($array);
    }

    /**
     * Delete stories that were published before a given date
     * @param         $timestamp
     * @param         $expired
     * @param  string $topicslist
     * @return bool
     */
    public function DeleteBeforeDate($timestamp, $expired, $topicslist = '')
    {
        global $xoopsModule;
        $mid          = $xoopsModule->getVar('mid');
        $db           = XoopsDatabaseFactory::getDatabaseConnection();
        $prefix       = $db->prefix('news_stories');
        $vote_prefix  = $db->prefix('news_stories_votedata');
        $files_prefix = $db->prefix('news_stories_files');
        $sql          = 'SELECT storyid FROM  ' . $prefix . ' WHERE published <=' . $timestamp;
        if ($expired) {
            $sql .= ' (AND expired>0 AND expired<=' . time() . ')';
        }
        if (strlen(trim($topicslist)) > 0) {
            $sql .= ' AND topicid IN (' . $topicslist . ')';
        }
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            xoops_comment_delete($mid, $myrow['storyid']); // Delete comments
            xoops_notification_deletebyitem($mid, 'story', $myrow['storyid']); // Delete notifications
            $db->queryF('DELETE FROM ' . $vote_prefix . ' WHERE storyid=' . $myrow['storyid']); // Delete votes
            // Remove files and records related to the files
            $result2 = $db->query('SELECT * FROM ' . $files_prefix . ' WHERE storyid=' . $myrow['storyid']);
            while ($myrow2 = $db->fetchArray($result2)) {
                $name = XOOPS_ROOT_PATH . '/uploads/' . $myrow2['downloadname'];
                if (file_exists($name)) {
                    unlink($name);
                }
                $db->query('DELETE FROM ' . $files_prefix . ' WHERE fileid=' . $myrow2['fileid']);
            }
            $db->queryF('DELETE FROM ' . $prefix . ' WHERE storyid=' . $myrow['storyid']); // Delete the story
        }

        return true;
    }

    /**
     * @param      $storyid
     * @param bool $next
     * @param bool $checkRight
     *
     * @return array
     */
    public function _searchPreviousOrNextArticle($storyid, $next = true, $checkRight = false)
    {
        $db      = XoopsDatabaseFactory::getDatabaseConnection();
        $ret     = array();
        $storyid = (int)$storyid;
        if ($next) {
            $sql     = 'SELECT storyid, title FROM '
                       . $db->prefix('news_stories')
                       . ' WHERE (published > 0 AND published <= '
                       . time()
                       . ') AND (expired = 0 OR expired > '
                       . time()
                       . ') AND storyid > '
                       . $storyid;
            $orderBy = ' ORDER BY storyid ASC';
        } else {
            $sql     = 'SELECT storyid, title FROM '
                       . $db->prefix('news_stories')
                       . ' WHERE (published > 0 AND published <= '
                       . time()
                       . ') AND (expired = 0 OR expired > '
                       . time()
                       . ') AND storyid < '
                       . $storyid;
            $orderBy = ' ORDER BY storyid DESC';
        }
        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            if (count($topics) > 0) {
                $sql .= ' AND topicid IN (' . implode(',', $topics) . ')';
            } else {
                return null;
            }
        }
        $sql .= $orderBy;
        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $result = $db->query($sql, 1);
        if ($result) {
            $myts = MyTextSanitizer::getInstance();
            while ($row = $db->fetchArray($result)) {
                $ret = array('storyid' => $row['storyid'], 'title' => $myts->htmlSpecialChars($row['title']));
            }
        }

        return $ret;
    }

    /**
     * @param      $storyid
     * @param bool $checkRight
     *
     * @return array
     */
    public function getNextArticle($storyid, $checkRight = false)
    {
        return $this->_searchPreviousOrNextArticle($storyid, true, $checkRight);
    }

    /**
     * @param      $storyid
     * @param bool $checkRight
     *
     * @return array
     */
    public function getPreviousArticle($storyid, $checkRight = false)
    {
        return $this->_searchPreviousOrNextArticle($storyid, false, $checkRight);
    }

    /**
     * Returns published stories according to some options
     * @param  int    $limit
     * @param  int    $start
     * @param  bool   $checkRight
     * @param  int    $topic
     * @param  int    $ihome
     * @param  bool   $asobject
     * @param  string $order
     * @param  bool   $topic_frontpage
     * @return array
     */
    public static function getAllPublished(
        $limit = 0,
        $start = 0,
        $checkRight = false,
        $topic = 0,
        $ihome = 0,
        $asobject = true,
        $order = 'published',
        $topic_frontpage = false
    ) {
        $db   = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();
        $ret  = array();
        $sql  = 'SELECT s.*, t.* FROM '
                . $db->prefix('news_stories')
                . ' s, '
                . $db->prefix('news_topics')
                . ' t WHERE (s.published > 0 AND s.published <= '
                . time()
                . ') AND (s.expired = 0 OR s.expired > '
                . time()
                . ') AND (s.topicid=t.topic_id) ';
        if ($topic != 0) {
            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = news_MygetItemIds('news_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    } else {
                        $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                    }
                } else {
                    $sql .= ' AND s.topicid=' . (int)$topic . ' AND (s.ihome=1 OR s.ihome=0)';
                }
            } else {
                if ($checkRight) {
                    $topics = news_MygetItemIds('news_view');
                    $topic  = array_intersect($topic, $topics);
                }
                if (count($topic) > 0) {
                    $sql .= ' AND s.topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = news_MygetItemIds('news_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql .= ' AND s.topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
            if ((int)$ihome == 0) {
                $sql .= ' AND s.ihome=0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql .= " ORDER BY s.$order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);

        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Retourne la liste des articles aux archives (pour une p�riode donn�e)
     * @param             $publish_start
     * @param             $publish_end
     * @param  bool       $checkRight
     * @param  bool       $asobject
     * @param  string     $order
     * @return array|null
     */
    public function getArchive(
        $publish_start,
        $publish_end,
        $checkRight = false,
        $asobject = true,
        $order = 'published'
    ) {
        $db   = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();
        $ret  = array();
        $sql  = 'SELECT s.*, t.* FROM '
                . $db->prefix('news_stories')
                . ' s, '
                . $db->prefix('news_topics')
                . ' t WHERE (s.topicid=t.topic_id) AND (s.published > '
                . $publish_start
                . ' AND s.published <= '
                . $publish_end
                . ') AND (expired = 0 OR expired > '
                . time()
                . ') ';

        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $sql .= ' AND topicid IN (' . $topics . ')';
            } else {
                return null;
            }
        }
        $sql .= " ORDER BY $order DESC";
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Get the today's most readed article
     *
     * @param int     $limit      records limit
     * @param int     $start      starting record
     * @param boolean $checkRight Do we need to check permissions (by topics) ?
     * @param int     $topic      limit the job to one topic
     * @param int     $ihome      Limit to articles published in home page only ?
     * @param boolean $asobject   Do we have to return an array of objects or a simple array ?
     * @param string  $order      Fields to sort on
     *
     * @return array
     */
    public function getBigStory(
        $limit = 0,
        $start = 0,
        $checkRight = false,
        $topic = 0,
        $ihome = 0,
        $asobject = true,
        $order = 'counter'
    ) {
        $db    = XoopsDatabaseFactory::getDatabaseConnection();
        $myts  = MyTextSanitizer::getInstance();
        $ret   = array();
        $tdate = mktime(0, 0, 0, date('n'), date('j'), date('Y'));
        $sql   = 'SELECT s.*, t.* FROM '
                 . $db->prefix('news_stories')
                 . ' s, '
                 . $db->prefix('news_topics')
                 . ' t WHERE (s.topicid=t.topic_id) AND (published > '
                 . $tdate
                 . ' AND published < '
                 . time()
                 . ') AND (expired > '
                 . time()
                 . ' OR expired = 0) ';

        if ((int)$topic != 0) {
            if (!is_array($topic)) {
                $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
            } else {
                if (count($topic) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = news_MygetItemIds('news_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql .= ' AND topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
            if ((int)$ihome == 0) {
                $sql .= ' AND ihome=0';
            }
        }
        $sql .= " ORDER BY $order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Get all articles published by an author
     *
     * @param int     $uid        author's id
     * @param boolean $checkRight whether to check the user's rights to topics
     *
     * @param bool    $asobject
     *
     * @return array
     */
    public function getAllPublishedByAuthor($uid, $checkRight = false, $asobject = true)
    {
        $db        = XoopsDatabaseFactory::getDatabaseConnection();
        $myts      = MyTextSanitizer::getInstance();
        $ret       = array();
        $tblstory  = $db->prefix('news_stories');
        $tbltopics = $db->prefix('news_topics');

        $sql = 'SELECT '
               . $tblstory
               . '.*, '
               . $tbltopics
               . '.topic_title, '
               . $tbltopics
               . '.topic_color FROM '
               . $tblstory
               . ','
               . $tbltopics
               . ' WHERE ('
               . $tblstory
               . '.topicid='
               . $tbltopics
               . '.topic_id) AND (published > 0 AND published <= '
               . time()
               . ') AND (expired = 0 OR expired > '
               . time()
               . ')';
        $sql .= ' AND uid=' . (int)$uid;
        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            $topics = implode(',', $topics);
            if (xoops_trim($topics) !== '') {
                $sql .= ' AND topicid IN (' . $topics . ')';
            }
        }
        $sql .= ' ORDER BY ' . $tbltopics . '.topic_title ASC, ' . $tblstory . '.published DESC';
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                if ($myrow['nohtml']) {
                    $html = 0;
                } else {
                    $html = 1;
                }
                if ($myrow['nosmiley']) {
                    $smiley = 0;
                } else {
                    $smiley = 1;
                }
                $ret[$myrow['storyid']] = array(
                    'title'       => $myts->displayTarea($myrow['title'], $html, $smiley, 1),
                    'topicid'     => (int)$myrow['topicid'],
                    'storyid'     => (int)$myrow['storyid'],
                    'hometext'    => $myts->displayTarea($myrow['hometext'], $html, $smiley, 1),
                    'counter'     => (int)$myrow['counter'],
                    'created'     => (int)$myrow['created'],
                    'topic_title' => $myts->displayTarea($myrow['topic_title'], $html, $smiley, 1),
                    'topic_color' => $myts->displayTarea($myrow['topic_color']),
                    'published'   => (int)$myrow['published'],
                    'rating'      => (float)$myrow['rating'],
                    'votes'       => (int)$myrow['votes']
                );
            }
        }

        return $ret;
    }

    /**
     * Get all expired stories
     * @param  int  $limit
     * @param  int  $start
     * @param  int  $topic
     * @param  int  $ihome
     * @param  bool $asobject
     * @return array
     */
    public static function getAllExpired($limit = 0, $start = 0, $topic = 0, $ihome = 0, $asobject = true)
    {
        $db   = XoopsDatabaseFactory::getDatabaseConnection();
        $myts = MyTextSanitizer::getInstance();
        $ret  = array();
        $sql  = 'SELECT * FROM ' . $db->prefix('news_stories') . ' WHERE expired <= ' . time() . ' AND expired > 0';
        if (!empty($topic)) {
            $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
        } else {
            if ((int)$ihome == 0) {
                $sql .= ' AND ihome=0';
            }
        }

        $sql .= ' ORDER BY expired DESC';
        $result = $db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Returns an array of object containing all the news to be automatically published.
     * @param  int  $limit
     * @param  bool $asobject
     * @param  int  $start
     * @return array
     */
    public static function getAllAutoStory($limit = 0, $asobject = true, $start = 0)
    {
        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $myts   = MyTextSanitizer::getInstance();
        $ret    = array();
        $sql    = 'SELECT * FROM ' . $db->prefix('news_stories') . ' WHERE published > ' . time() . ' ORDER BY published ASC';
        $result = $db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Get all submitted stories awaiting approval
     *
     * @param int     $limit      Denotes where to start the query
     * @param boolean $asobject   true will returns the stories as an array of objects, false will return storyid => title
     * @param boolean $checkRight whether to check the user's rights to topics
     *
     * @param int     $start
     *
     * @return array
     */
    public static function getAllSubmitted($limit = 0, $asobject = true, $checkRight = false, $start = 0)
    {
        $db       = XoopsDatabaseFactory::getDatabaseConnection();
        $myts     = MyTextSanitizer::getInstance();
        $ret      = array();
        $criteria = new CriteriaCompo(new Criteria('published', 0));
        if ($checkRight) {
            global $xoopsUser;
            if (!is_object($xoopsUser)) {
                return $ret;
            }
            $allowedtopics = news_MygetItemIds('news_approve');
            $criteria2     = new CriteriaCompo();
            foreach ($allowedtopics as $key => $topicid) {
                $criteria2->add(new Criteria('topicid', $topicid), 'OR');
            }
            $criteria->add($criteria2);
        }
        $sql = 'SELECT s.*, t.* FROM ' . $db->prefix('news_stories') . ' s, ' . $db->prefix('news_topics') . ' t ';
        $sql .= ' ' . $criteria->renderWhere() . ' AND (s.topicid=t.topic_id) ORDER BY created DESC';
        $result = $db->query($sql, (int)$limit, (int)$start);
        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Used in the module's admin to know the number of expired, automated or pubilshed news
     *
     * @param int  $storytype  1=Expired, 2=Automated, 3=New submissions, 4=Last published stories
     * @param bool $checkRight verify permissions or not ?
     *
     * @return int
     */
    public static function getAllStoriesCount($storytype = 1, $checkRight = false)
    {
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = 'SELECT count(*) as cpt FROM ' . $db->prefix('news_stories') . ' WHERE ';
        switch ($storytype) {
            case 1: // Expired
                $sql .= '(expired <= ' . time() . ' AND expired >0)';
                break;
            case 2: // Automated
                $sql .= '(published > ' . time() . ')';
                break;
            case 3: // New submissions
                $sql .= '(published = 0)';
                break;
            case 4: // Last published stories
                $sql .= '(published > 0 AND published <= ' . time() . ') AND (expired = 0 OR expired > ' . time() . ')';
                break;
        }
        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $sql .= ' AND topicid IN (' . $topics . ')';
            } else {
                return 0;
            }
        }
        $result = $db->query($sql);
        $myrow  = $db->fetchArray($result);

        return $myrow['cpt'];
    }

    /**
     * Get a list of stories (as objects) related to a specific topic
     * @param        $topicid
     * @param  int   $limit
     * @return array
     */
    public static function getByTopic($topicid, $limit = 0)
    {
        $ret    = array();
        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $sql    = 'SELECT * FROM ' . $db->prefix('news_stories') . ' WHERE topicid=' . (int)$topicid . ' ORDER BY published DESC';
        $result = $db->query($sql, (int)$limit, 0);
        while ($myrow = $db->fetchArray($result)) {
            $ret[] = new NewsStory($myrow);
        }

        return $ret;
    }

    /**
     * Count the number of news published for a specific topic
     * @param  int  $topicid
     * @param  bool $checkRight
     * @return null
     */
    public static function countPublishedByTopic($topicid = 0, $checkRight = false)
    {
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        $sql = 'SELECT COUNT(*) FROM '
               . $db->prefix('news_stories')
               . ' WHERE published > 0 AND published <= '
               . time()
               . ' AND (expired = 0 OR expired > '
               . time()
               . ')';
        if (!empty($topicid)) {
            $sql .= ' AND topicid=' . (int)$topicid;
        } else {
            $sql .= ' AND ihome=0';
            if ($checkRight) {
                $topics = news_MygetItemIds('news_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql .= ' AND topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
        }
        $result = $db->query($sql);
        list($count) = $db->fetchRow($result);

        return $count;
    }

    /**
     * Internal function
     */
    public function adminlink()
    {
        global $xoopsModule;
        $dirname = basename(dirname(__DIR__));
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($dirname);
        $pathIcon16    = $module->getInfo('icons16');

        $ret = '&nbsp; <a href='
               . XOOPS_URL
               . '/modules/news/submit.php?op=edit&amp;storyid='
               . $this->storyid()
               . '><img src='
               . $pathIcon16
               . '/edit.png'
               . ' '
               . 'title='
               . _NW_EDIT
               . '></a>'
               . '<a href='
               . XOOPS_URL
               . '/modules/news/admin/index.php?op=delete&amp;storyid='
               . $this->storyid()
               . '><img src='
               . $pathIcon16
               . '/delete.png'
               . ' '
               . 'title='
               . _NW_DELETE
               . '></a> &nbsp;';

        return $ret;
    }

    /**
     * Get the topic image url
     * @param string $format
     * @return
     */
    public function topic_imgurl($format = 'S')
    {
        if (trim($this->topic_imgurl) === '') {
            $this->topic_imgurl = 'blank.png';
        }
        $myts = MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $imgurl = $myts->htmlSpecialChars($this->topic_imgurl);
                break;
            case 'E':
                $imgurl = $myts->htmlSpecialChars($this->topic_imgurl);
                break;
            case 'P':
                $imgurl = $myts->stripSlashesGPC($this->topic_imgurl);
                $imgurl = $myts->htmlSpecialChars($imgurl);
                break;
            case 'F':
                $imgurl = $myts->stripSlashesGPC($this->topic_imgurl);
                $imgurl = $myts->htmlSpecialChars($imgurl);
                break;
        }

        return $imgurl;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function topic_title($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
                $title = $myts->htmlSpecialChars($this->topic_title);
                break;
            case 'E':
                $title = $myts->htmlSpecialChars($this->topic_title);
                break;
            case 'P':
                $title = $myts->stripSlashesGPC($this->topic_title);
                $title = $myts->htmlSpecialChars($title);
                break;
            case 'F':
                $title = $myts->stripSlashesGPC($this->topic_title);
                $title = $myts->htmlSpecialChars($title);
                break;
        }

        return $title;
    }

    /**
     * @return string
     */
    public function imglink()
    {
        $ret = '';
        if ($this->topic_imgurl() !== ''
            && file_exists(XOOPS_ROOT_PATH . '/uploads/news/image/' . $this->topic_imgurl())
        ) {
            $ret = "<a href='"
                   . XOOPS_URL
                   . '/modules/news/index.php?storytopic='
                   . $this->topicid()
                   . "'><img src='"
                   . XOOPS_URL
                   . '/uploads/news/image/'
                   . $this->topic_imgurl()
                   . "' alt='"
                   . $this->topic_title()
                   . "' hspace='10' vspace='10' align='"
                   . $this->topicalign()
                   . "' /></a>";
        }

        return $ret;
    }

    /**
     * @return string
     */
    public function textlink()
    {
        $ret = '<a titls='
               . $this->topic_title()
               . " href='"
               . XOOPS_URL
               . '/modules/news/index.php?storytopic='
               . $this->topicid()
               . "'>"
               . $this->topic_title()
               . '</a>';

        return $ret;
    }

    /**
     * Function used to prepare an article to be showned
     * @param $filescount
     * @return array
     */
    public function prepare2show($filescount)
    {
        include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
        global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

        $dirname = basename(dirname(__DIR__));
        /** @var XoopsModuleHandler $moduleHandler */
        $moduleHandler = xoops_getHandler('module');
        $module        = $moduleHandler->getByDirname($dirname);
        $pathIcon16    = $module->getInfo('icons16');

        $myts                 = MyTextSanitizer::getInstance();
        $infotips             = news_getmoduleoption('infotips');
        $story                = array();
        $story['id']          = $this->storyid();
        $story['poster']      = $this->uname();
        $story['author_name'] = $this->uname();
        $story['author_uid']  = $this->uid();
        if ($story['poster'] !== false) {
            $story['poster'] = "<a href='" . XOOPS_URL . '/userinfo.php?uid=' . $this->uid() . "'>" . $story['poster'] . '</a>';
        } else {
            if ($xoopsModuleConfig['displayname'] != 3) {
                $story['poster'] = $xoopsConfig['anonymous'];
            }
        }
        if ($xoopsModuleConfig['ratenews']) {
            $story['rating'] = number_format($this->rating(), 2);
            if ($this->votes == 1) {
                $story['votes'] = _NW_ONEVOTE;
            } else {
                $story['votes'] = sprintf(_NW_NUMVOTES, $this->votes);
            }
        }
        $story['posttimestamp']     = $this->published();
        $story['posttime']          = formatTimestamp($story['posttimestamp'], news_getmoduleoption('dateformat'));
        $story['topic_description'] = $myts->displayTarea($this->topic_description);

        $auto_summary = '';
        $tmp          = '';
        $auto_summary = $this->auto_summary($this->bodytext(), $tmp);

        $story['text'] = $this->hometext();
        $story['text'] = str_replace('[summary]', $auto_summary, $story['text']);

        //$story['picture'] = XOOPS_URL.'/uploads/news/image/'.$this->picture();
        if ($this->picture() !== '') {
            $story['picture'] = XOOPS_URL . '/uploads/news/image/' . $this->picture();
        } else {
            $story['picture'] = '';
        }
        $story['pictureinfo'] = $this->pictureinfo();

        $introcount = strlen($story['text']);
        $fullcount  = strlen($this->bodytext());
        $totalcount = $introcount + $fullcount;

        $morelink = '';
        if ($fullcount > 1) {
            $morelink .= '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            $morelink .= '">' . _NW_READMORE . '</a>';
            $morelink .= ' | ' . sprintf(_NW_BYTESMORE, $totalcount);
            if (XOOPS_COMMENT_APPROVENONE != $xoopsModuleConfig['com_rule']) {
                $morelink .= ' | ';
            }
        }
        if (XOOPS_COMMENT_APPROVENONE != $xoopsModuleConfig['com_rule']) {
            $ccount = $this->comments();
            $morelink .= '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            $morelink2 = '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $this->storyid() . '';
            if ($ccount == 0) {
                $morelink .= '">' . _NW_COMMENTS . '</a>';
            } else {
                if ($fullcount < 1) {
                    if ($ccount == 1) {
                        $morelink .= '">' . _NW_READMORE . '</a> | ' . $morelink2 . '">' . _NW_ONECOMMENT . '</a>';
                    } else {
                        $morelink .= '">' . _NW_READMORE . '</a> | ' . $morelink2 . '">';
                        $morelink .= sprintf(_NW_NUMCOMMENTS, $ccount);
                        $morelink .= '</a>';
                    }
                } else {
                    if ($ccount == 1) {
                        $morelink .= '">' . _NW_ONECOMMENT . '</a>';
                    } else {
                        $morelink .= '">';
                        $morelink .= sprintf(_NW_NUMCOMMENTS, $ccount);
                        $morelink .= '</a>';
                    }
                }
            }
        }
        $story['morelink']  = $morelink;
        $story['adminlink'] = '';

        $approveprivilege = 0;
        if (news_is_admin_group()) {
            $approveprivilege = 1;
        }

        if ($xoopsModuleConfig['authoredit'] == 1
            && (is_object($xoopsUser)
                && $xoopsUser->getVar('uid') == $this->uid())
        ) {
            $approveprivilege = 1;
        }
        if ($approveprivilege) {
            $story['adminlink'] = $this->adminlink();
        }
        $story['mail_link'] = 'mailto:?subject='
                              . sprintf(_NW_INTARTICLE, $xoopsConfig['sitename'])
                              . '&amp;body='
                              . sprintf(_NW_INTARTFOUND, $xoopsConfig['sitename'])
                              . ':  '
                              . XOOPS_URL
                              . '/modules/news/article.php?storyid='
                              . $this->storyid();
        $story['imglink']   = '';
        $story['align']     = '';
        if ($this->topicdisplay()) {
            $story['imglink'] = $this->imglink();
            $story['align']   = $this->topicalign();
        }
        if ($infotips > 0) {
            $story['infotips'] = ' title="' . news_make_infotips($this->hometext()) . '"';
        } else {
            $story['infotips'] = 'title="' . $this->title() . '"';
        }
        $story['title'] = "<a href='"
                          . XOOPS_URL
                          . '/modules/news/article.php?storyid='
                          . $this->storyid()
                          . "'"
                          . $story['infotips']
                          . '>'
                          . $this->title()
                          . '</a>';
        //$story['subtitle'] = $this->subtitle();

        $story['hits'] = $this->counter();
        if ($filescount > 0) {
            $story['files_attached'] = true;
            $story['attached_link']  = "<a href='"
                                       . XOOPS_URL
                                       . '/modules/news/article.php?storyid='
                                       . $this->storyid()
                                       . "' title='"
                                       . _NW_ATTACHEDLIB
                                       . "'><img src="
                                       . $pathIcon16
                                       . '/attach.png'
                                       . ' '
                                       . 'title='
                                       . _NW_ATTACHEDLIB
                                       . '></a>';
        } else {
            $story['files_attached'] = false;
            $story['attached_link']  = '';
        }

        return $story;
    }

    /**
     * Returns the user's name of the current story according to the module's option "displayname"
     * @param  int $uid
     * @return null|string
     */
    public function uname($uid = 0)
    {
        global $xoopsConfig;
        include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
        static $tblusers = array();
        $option = -1;
        if ($uid == 0) {
            $uid = $this->uid();
        }

        if (is_array($tblusers) && array_key_exists($uid, $tblusers)) {
            return $tblusers[$uid];
        }

        $option = news_getmoduleoption('displayname');
        if (!$option) {
            $option = 1;
        }

        switch ($option) {
            case 1: // Username
                $tblusers[$uid] = XoopsUser::getUnameFromId($uid);

                return $tblusers[$uid];

            case 2: // Display full name (if it is not empty)
                $member_handler = xoops_getHandler('member');
                $thisuser       = $member_handler->getUser($uid);
                if (is_object($thisuser)) {
                    $return = $thisuser->getVar('name');
                    if ($return === '') {
                        $return = $thisuser->getVar('uname');
                    }
                } else {
                    $return = $xoopsConfig['anonymous'];
                }
                $tblusers[$uid] = $return;

                return $return;

            case 3: // Nothing
                $tblusers[$uid] = '';

                return '';
        }

        return null;
    }

    /**
     * Function used to export news (in xml) and eventually the topics definitions
     * Warning, permissions are not exported !
     *
     * @param int      $fromdate     Starting date
     * @param int      $todate       Ending date
     * @param string   $topicslist
     * @param bool|int $usetopicsdef Should we also export topics definitions ?
     * @param          $tbltopics
     * @param boolean  $asobject     Return values as an object or not ?
     *
     * @param string   $order
     *
     * @internal param string $topiclist If not empty, a list of topics to limit to
     * @return array
     */
    public function NewsExport(
        $fromdate,
        $todate,
        $topicslist = '',
        $usetopicsdef = 0,
        &$tbltopics,
        $asobject = true,
        $order = 'published'
    ) {
        $ret  = array();
        $myts = MyTextSanitizer::getInstance();
        if ($usetopicsdef) { // We firt begin by exporting topics definitions
            // Before all we must know wich topics to export
            $sql = 'SELECT distinct topicid FROM '
                   . $this->db->prefix('news_stories')
                   . ' WHERE (published >='
                   . $fromdate
                   . ' AND published <= '
                   . $todate
                   . ')';
            if (strlen(trim($topicslist)) > 0) {
                $sql .= ' AND topicid IN (' . $topicslist . ')';
            }
            $result = $this->db->query($sql);
            while ($myrow = $this->db->fetchArray($result)) {
                $tbltopics[] = $myrow['topicid'];
            }
        }

        // Now we can search for the stories
        $sql = 'SELECT s.*, t.* FROM '
               . $this->table
               . ' s, '
               . $this->db->prefix('news_topics')
               . ' t WHERE (s.topicid=t.topic_id) AND (s.published >='
               . $fromdate
               . ' AND s.published <= '
               . $todate
               . ')';
        if (strlen(trim($topicslist)) > 0) {
            $sql .= ' AND topicid IN (' . $topicslist . ')';
        }
        $sql .= " ORDER BY $order DESC";
        $result = $this->db->query($sql);
        while ($myrow = $this->db->fetchArray($result)) {
            if ($asobject) {
                $ret[] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }

    /**
     * Create or update an article
     * @param  bool $approved
     * @return bool|int
     */
    public function store($approved = false)
    {
        $myts        = MyTextSanitizer::getInstance();
        $counter     = isset($this->counter) ? $this->counter : 0;
        $title       = $myts->addSlashes($myts->censorString($this->title));
        $subtitle    = $myts->addSlashes($myts->censorString($this->subtitle));
        $hostname    = $myts->addSlashes($this->hostname);
        $type        = $myts->addSlashes($this->type);
        $hometext    = $myts->addSlashes($myts->censorString($this->hometext));
        $bodytext    = $myts->addSlashes($myts->censorString($this->bodytext));
        $description = $myts->addSlashes($myts->censorString($this->description));
        $keywords    = $myts->addSlashes($myts->censorString($this->keywords));
        $picture     = $myts->addSlashes($this->picture);
        $pictureinfo = $myts->addSlashes($myts->censorString($this->pictureinfo));
        $votes       = (int)$this->votes;
        $rating      = (float)$this->rating;
        if (!isset($this->nohtml) || $this->nohtml != 1) {
            $this->nohtml = 0;
        }
        if (!isset($this->nosmiley) || $this->nosmiley != 1) {
            $this->nosmiley = 0;
        }
        if (!isset($this->notifypub) || $this->notifypub != 1) {
            $this->notifypub = 0;
        }
        if (!isset($this->topicdisplay) || $this->topicdisplay != 0) {
            $this->topicdisplay = 1;
        }
        $expired = !empty($this->expired) ? $this->expired : 0;
        if (!isset($this->storyid)) {
            //$newpost = 1;
            $newstoryid = $this->db->genId($this->table . '_storyid_seq');
            $created    = time();
            $published  = $this->approved ? (int)$this->published : 0;
            $sql        = sprintf("INSERT INTO %s (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments, rating, votes, description, keywords, picture, pictureinfo, subtitle) VALUES (%u, %u, '%s', %u, %u, %u, '%s', %u, %u, '%s', '%s', %u, %u, %u, %u, '%s', %u, '%s', %u, %u, %u, '%s', '%s', '%s', '%s', '%s')",
                                  $this->table, $newstoryid, (int)$this->uid(), $title, $created, $published, $expired, $hostname,
                                  (int)$this->nohtml(), (int)$this->nosmiley(), $hometext, $bodytext, $counter, (int)$this->topicid(),
                                  (int)$this->ihome(), (int)$this->notifypub(), $type, (int)$this->topicdisplay(), $this->topicalign,
                                  (int)$this->comments(), $rating, $votes, $description, $keywords, $picture, $pictureinfo, $subtitle);
        } else {
            $sql        = sprintf("UPDATE %s SET title='%s', published=%u, expired=%u, nohtml=%u, nosmiley=%u, hometext='%s', bodytext='%s', topicid=%u, ihome=%u, topicdisplay=%u, topicalign='%s', comments=%u, rating=%u, votes=%u, uid=%u, description='%s', keywords='%s', picture='%s' , pictureinfo='%s' , subtitle='%s' WHERE storyid = %u",
                                  $this->table, $title, (int)$this->published(), $expired, (int)$this->nohtml(), (int)$this->nosmiley(), $hometext,
                                  $bodytext, (int)$this->topicid(), (int)$this->ihome(), (int)$this->topicdisplay(), $this->topicalign,
                                  (int)$this->comments(), $rating, $votes, (int)$this->uid(), $description, $keywords, $picture, $pictureinfo,
                                  $subtitle, (int)$this->storyid());
            $newstoryid = (int)$this->storyid();
        }
        if (!$this->db->queryF($sql)) {
            return false;
        }
        if (empty($newstoryid)) {
            $newstoryid    = $this->db->getInsertId();
            $this->storyid = $newstoryid;
        }

        return $newstoryid;
    }

    /**
     * @return mixed
     */
    public function picture()
    {
        return $this->picture;
    }

    /**
     * @return mixed
     */
    public function pictureinfo()
    {
        return $this->pictureinfo;
    }

    /**
     * @return mixed
     */
    public function subtitle()
    {
        return $this->subtitle;
    }

    /**
     * @return mixed
     */
    public function rating()
    {
        return $this->rating;
    }

    /**
     * @return mixed
     */
    public function votes()
    {
        return $this->votes;
    }

    /**
     * @param $data
     */
    public function setPicture($data)
    {
        $this->picture = $data;
    }

    /**
     * @param $data
     */
    public function setPictureinfo($data)
    {
        $this->pictureinfo = $data;
    }

    /**
     * @param $data
     */
    public function setSubtitle($data)
    {
        $this->subtitle = $data;
    }

    /**
     * @param $data
     */
    public function setDescription($data)
    {
        $this->description = $data;
    }

    /**
     * @param $data
     */
    public function setKeywords($data)
    {
        $this->keywords = $data;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function description($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();
        switch (strtoupper($format)) {
            case 'S':
                $description = $myts->htmlSpecialChars($this->description);
                break;
            case 'P':
            case 'F':
                $description = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->description));
                break;
            case 'E':
                $description = $myts->htmlSpecialChars($this->description);
                break;
        }

        return $description;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function keywords($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();
        switch (strtoupper($format)) {
            case 'S':
                $keywords = $myts->htmlSpecialChars($this->keywords);
                break;
            case 'P':
            case 'F':
                $keywords = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->keywords));
                break;
            case 'E':
                $keywords = $myts->htmlSpecialChars($this->keywords);
                break;
        }

        return $keywords;
    }

    /**
     * Returns a random number of news
     * @param  int    $limit
     * @param  int    $start
     * @param  bool   $checkRight
     * @param  int    $topic
     * @param  int    $ihome
     * @param  string $order
     * @param  bool   $topic_frontpage
     * @return array
     */
    public function getRandomNews(
        $limit = 0,
        $start = 0,
        $checkRight = false,
        $topic = 0,
        $ihome = 0,
        $order = 'published',
        $topic_frontpage = false
    ) {
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        $ret = $rand_keys = $ret3 = array();
        $sql = 'SELECT storyid FROM '
               . $db->prefix('news_stories')
               . ' WHERE (published > 0 AND published <= '
               . time()
               . ') AND (expired = 0 OR expired > '
               . time()
               . ')';
        if ($topic != 0) {
            if (!is_array($topic)) {
                if ($checkRight) {
                    $topics = news_MygetItemIds('news_view');
                    if (!in_array($topic, $topics)) {
                        return null;
                    } else {
                        $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
                    }
                } else {
                    $sql .= ' AND topicid=' . (int)$topic . ' AND (ihome=1 OR ihome=0)';
                }
            } else {
                if (count($topic) > 0) {
                    $sql .= ' AND topicid IN (' . implode(',', $topic) . ')';
                } else {
                    return null;
                }
            }
        } else {
            if ($checkRight) {
                $topics = news_MygetItemIds('news_view');
                if (count($topics) > 0) {
                    $topics = implode(',', $topics);
                    $sql .= ' AND topicid IN (' . $topics . ')';
                } else {
                    return null;
                }
            }
            if ((int)$ihome == 0) {
                $sql .= ' AND ihome=0';
            }
        }
        if ($topic_frontpage) {
            $sql .= ' AND t.topic_frontpage=1';
        }
        $sql .= " ORDER BY $order DESC";
        $result = $db->query($sql);

        while ($myrow = $db->fetchArray($result)) {
            $ret[] = $myrow['storyid'];
        }
        $cnt = count($ret);
        if ($cnt) {
            mt_srand((double)microtime() * 10000000);
            if ($limit > $cnt) {
                $limit = $cnt;
            }
            $rand_keys = array_rand($ret, $limit);
            if ($limit > 1) {
                for ($i = 0; $i < $limit; ++$i) {
                    $onestory = $ret[$rand_keys[$i]];
                    $ret3[]   = new NewsStory($onestory);
                }
            } else {
                $ret3[] = new NewsStory($ret[$rand_keys]);
            }
        }

        return $ret3;
    }

    /**
     * Returns statistics about the stories and topics
     * @param $limit
     * @return array
     */
    public function GetStats($limit)
    {
        $ret  = array();
        $db   = XoopsDatabaseFactory::getDatabaseConnection();
        $tbls = $db->prefix('news_stories');
        $tblt = $db->prefix('news_topics');
        $tblf = $db->prefix('news_stories_files');

        $db = XoopsDatabaseFactory::getDatabaseConnection();
        // Number of stories per topic, including expired and non published stories
        $ret2   = array();
        $sql    = "SELECT count(s.storyid) as cpt, s.topicid, t.topic_title FROM $tbls s, $tblt t WHERE s.topicid=t.topic_id GROUP BY s.topicid ORDER BY t.topic_title";
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow;
        }
        $ret['storiespertopic'] = $ret2;
        unset($ret2);

        // Total of reads per topic
        $ret2   = array();
        $sql    = "SELECT Sum(counter) as cpt, topicid FROM $tbls GROUP BY topicid ORDER BY topicid";
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['readspertopic'] = $ret2;
        unset($ret2);

        // Attached files per topic
        $ret2   = array();
        $sql    = "SELECT Count(*) as cpt, s.topicid FROM $tblf f, $tbls s WHERE f.storyid=s.storyid GROUP BY s.topicid ORDER BY s.topicid";
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['filespertopic'] = $ret2;
        unset($ret2);

        // Expired articles per topic
        $ret2   = array();
        $sql    = "SELECT Count(storyid) as cpt, topicid FROM $tbls WHERE expired>0 AND expired<=" . time() . ' GROUP BY topicid ORDER BY topicid';
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['expiredpertopic'] = $ret2;
        unset($ret2);

        // Number of unique authors per topic
        $ret2   = array();
        $sql    = "SELECT Count(Distinct(uid)) as cpt, topicid FROM $tbls GROUP BY topicid ORDER BY topicid";
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['topicid']] = $myrow['cpt'];
        }
        $ret['authorspertopic'] = $ret2;
        unset($ret2);

        // Most readed articles
        $ret2   = array();
        $sql    = "SELECT s.storyid, s.uid, s.title, s.counter, s.topicid, t.topic_title  FROM $tbls s, $tblt t WHERE s.topicid=t.topic_id ORDER BY s.counter DESC";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['mostreadednews'] = $ret2;
        unset($ret2);

        // Less readed articles
        $ret2   = array();
        $sql    = "SELECT s.storyid, s.uid, s.title, s.counter, s.topicid, t.topic_title  FROM $tbls s, $tblt t WHERE s.topicid=t.topic_id ORDER BY s.counter";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['lessreadednews'] = $ret2;
        unset($ret2);

        // Best rated articles
        $ret2   = array();
        $sql    = "SELECT s.storyid, s.uid, s.title, s.rating, s.topicid, t.topic_title  FROM $tbls s, $tblt t WHERE s.topicid=t.topic_id ORDER BY s.rating DESC";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['storyid']] = $myrow;
        }
        $ret['besratednews'] = $ret2;
        unset($ret2);

        // Most readed authors
        $ret2   = array();
        $sql    = "SELECT Sum(counter) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['mostreadedauthors'] = $ret2;
        unset($ret2);

        // Best rated authors
        $ret2   = array();
        $sql    = "SELECT Avg(rating) as cpt, uid FROM $tbls WHERE votes > 0 GROUP BY uid ORDER BY cpt DESC";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['bestratedauthors'] = $ret2;
        unset($ret2);

        // Biggest contributors
        $ret2   = array();
        $sql    = "SELECT Count(*) as cpt, uid FROM $tbls GROUP BY uid ORDER BY cpt DESC";
        $result = $db->query($sql, (int)$limit);
        while ($myrow = $db->fetchArray($result)) {
            $ret2[$myrow['uid']] = $myrow['cpt'];
        }
        $ret['biggestcontributors'] = $ret2;
        unset($ret2);

        return $ret;
    }

    /**
     * Get the date of the older and most recent news
     * @param $older
     * @param $recent
     */
    public function GetOlderRecentNews(&$older, &$recent)
    {
        $db     = XoopsDatabaseFactory::getDatabaseConnection();
        $sql    = 'SELECT min(published) as minpublish, max(published) as maxpublish FROM ' . $db->prefix('news_stories');
        $result = $db->query($sql);
        if (!$result) {
            $older = $recent = 0;
        } else {
            list($older, $recent) = $this->db->fetchRow($result);
        }
    }

    /*
     * Returns the author's IDs for the Who's who page
     */
    /**
     * @param bool $checkRight
     * @param int  $limit
     * @param int  $start
     *
     * @return array|null
     */
    public function getWhosWho($checkRight = false, $limit = 0, $start = 0)
    {
        $db  = XoopsDatabaseFactory::getDatabaseConnection();
        $ret = array();
        $sql = 'SELECT distinct(uid) as uid FROM '
               . $db->prefix('news_stories')
               . ' WHERE (published > 0 AND published <= '
               . time()
               . ') AND (expired = 0 OR expired > '
               . time()
               . ')';
        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $sql .= ' AND topicid IN (' . $topics . ')';
            } else {
                return null;
            }
        }
        $sql .= ' ORDER BY uid';
        $result = $db->query($sql);
        while ($myrow = $db->fetchArray($result)) {
            $ret[] = $myrow['uid'];
        }

        return $ret;
    }

    /**
     * Returns the content of the summary and the titles requires for the list selector
     * @param $text
     * @param $titles
     * @return string
     */
    public function auto_summary($text, &$titles)
    {
        $auto_summary = '';
        if (news_getmoduleoption('enhanced_pagenav')) {
            $expr_matches = array();
            $posdeb       = preg_match_all('/(\[pagebreak:|\[pagebreak).*\]/iU', $text, $expr_matches);
            if (count($expr_matches) > 0) {
                $delimiters  = $expr_matches[0];
                $arr_search  = array('[pagebreak:', '[pagebreak', ']');
                $arr_replace = array('', '', '');
                $cpt         = 1;
                if (isset($titles) && is_array($titles)) {
                    $titles[] = strip_tags(sprintf(_NW_PAGE_AUTO_SUMMARY, 1, $this->title()));
                }
                $item = "<a href='"
                        . XOOPS_URL
                        . '/modules/news/article.php?storyid='
                        . $this->storyid()
                        . "&page=0'>"
                        . sprintf(_NW_PAGE_AUTO_SUMMARY, 1, $this->title())
                        . '</a><br>';
                $auto_summary .= $item;

                foreach ($delimiters as $item) {
                    ++$cpt;
                    $item = str_replace($arr_search, $arr_replace, $item);
                    if (xoops_trim($item) == '') {
                        $item = $cpt;
                    }
                    $titles[] = strip_tags(sprintf(_NW_PAGE_AUTO_SUMMARY, $cpt, $item));
                    $item     = "<a href='"
                                . XOOPS_URL
                                . '/modules/news/article.php?storyid='
                                . $this->storyid()
                                . '&page='
                                . ($cpt - 1)
                                . "'>"
                                . sprintf(_NW_PAGE_AUTO_SUMMARY, $cpt, $item)
                                . '</a><br>';
                    $auto_summary .= $item;
                }
            }
        }

        return $auto_summary;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function hometext($format = 'Show')
    {
        $myts = MyTextSanitizer::getInstance();
        $html = $smiley = $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
                $hometext     = $myts->displayTarea($this->hometext, $html, $smiley, $xcodes);
                $tmp          = '';
                $auto_summary = $this->auto_summary($this->bodytext('Show'), $tmp);
                $hometext     = str_replace('[summary]', $auto_summary, $hometext);
                break;
            case 'Edit':
                $hometext = $myts->htmlSpecialChars($this->hometext);
                break;
            case 'Preview':
                $hometext = $myts->previewTarea($this->hometext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $hometext = $myts->stripSlashesGPC($this->hometext);
                $hometext = $myts->htmlSpecialChars($hometext);
                break;
        }

        return $hometext;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function bodytext($format = 'Show')
    {
        $myts   = MyTextSanitizer::getInstance();
        $html   = 1;
        $smiley = 1;
        $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
                $bodytext     = $myts->displayTarea($this->bodytext, $html, $smiley, $xcodes);
                $tmp          = '';
                $auto_summary = $this->auto_summary($bodytext, $tmp);
                $bodytext     = str_replace('[summary]', $auto_summary, $bodytext);
                break;
            case 'Edit':
                $bodytext = $myts->htmlSpecialChars($this->bodytext);
                break;
            case 'Preview':
                $bodytext = $myts->previewTarea($this->bodytext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $bodytext = $myts->stripSlashesGPC($this->bodytext);
                $bodytext = $myts->htmlSpecialChars($bodytext);
                break;
        }

        return $bodytext;
    }

    /**
     * Returns stories by Ids
     * @param             $ids
     * @param  bool       $checkRight
     * @param  bool       $asobject
     * @param  string     $order
     * @param  bool       $onlyOnline
     * @return array|null
     */
    public function getStoriesByIds(
        $ids,
        $checkRight = true,
        $asobject = true,
        $order = 'published',
        $onlyOnline = true
    ) {
        $limit = $start = 0;
        $db    = XoopsDatabaseFactory::getDatabaseConnection();
        $myts  = MyTextSanitizer::getInstance();
        $ret   = array();
        $sql   = 'SELECT s.*, t.* FROM ' . $db->prefix('news_stories') . ' s, ' . $db->prefix('news_topics') . ' t WHERE ';
        if (is_array($ids) && count($ids) > 0) {
            array_walk($ids, 'intval');
        }
        $sql .= ' s.storyid IN (' . implode(',', $ids) . ') ';

        if ($onlyOnline) {
            $sql .= ' AND (s.published > 0 AND s.published <= ' . time() . ') AND (s.expired = 0 OR s.expired > ' . time() . ') ';
        }
        $sql .= ' AND (s.topicid=t.topic_id) ';
        if ($checkRight) {
            $topics = news_MygetItemIds('news_view');
            if (count($topics) > 0) {
                $topics = implode(',', $topics);
                $sql .= ' AND s.topicid IN (' . $topics . ')';
            } else {
                return null;
            }
        }
        $sql .= " ORDER BY s.$order DESC";
        $result = $db->query($sql, (int)$limit, (int)$start);

        while ($myrow = $db->fetchArray($result)) {
            if ($asobject) {
                $ret[$myrow['storyid']] = new NewsStory($myrow);
            } else {
                $ret[$myrow['storyid']] = $myts->htmlSpecialChars($myrow['title']);
            }
        }

        return $ret;
    }
}

/**
 * Class news_NewsStoryHandler
 */
class news_NewsStoryHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|XoopsDatabase $db
     */
    public function __construct(XoopsDatabase $db)
    {
        parent::__construct($db, 'news_stories', 'stories', 'storieid', 'title');
    }
}
