<?php declare(strict_types=1);

namespace XoopsModules\News;

/**
 * XOOPS news topic
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       XOOPS Project (https://xoops.org)
 * @license         GNU GPL 2 (https://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @deprecated
 */

//$GLOBALS['xoopsLogger']->addDeprecated("'/class/xoopstopic.php' is deprecated since XOOPS 2.5.4, please create your own class instead.");

// require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopstree.php';

use MyTextSanitizer;
use XoopsDatabaseFactory;
use XoopsModules\News;
use XoopsPerms;

/**
 * Class XoopsTopic
 */
class XoopsTopic
{
    public $table;
    public $topic_id;
    public $topic_pid;
    public $topic_title;
    public $topic_imgurl;
    public $prefix; // only used in topic tree
    public $use_permission = false;
    public $mid; // module id used for setting permission

    /**
     * @param     $table
     * @param int $topicid
     */
    public function __construct($table, $topicid = 0)
    {
        /** @var \XoopsMySQLDatabase $db */
        $this->db    = XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = $table;
        if (\is_array($topicid)) {
            $this->makeTopic($topicid);
        } elseif (0 != $topicid) {
            $this->getTopic((int)$topicid);
        } else {
            $this->topic_id = $topicid;
        }
    }

    /**
     * @param $value
     */
    public function setTopicTitle($value): void
    {
        $this->topic_title = $value;
    }

    /**
     * @param $value
     */
    public function setTopicImgurl($value): void
    {
        $this->topic_imgurl = $value;
    }

    /**
     * @param $value
     */
    public function setTopicPid($value): void
    {
        $this->topic_pid = $value;
    }

    /**
     * @param $topicid
     */
    public function getTopic($topicid): void
    {
        $topicid = (int)$topicid;
        $sql     = 'SELECT * FROM ' . $this->table . ' WHERE topic_id=' . $topicid;
        $array   = $this->db->fetchArray($this->db->query($sql));
        $this->makeTopic($array);
    }

    /**
     * @param $array
     */
    public function makeTopic($array): void
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param $mid
     */
    public function usePermission($mid): void
    {
        $this->mid            = $mid;
        $this->use_permission = true;
    }

    /**
     * @return bool
     */
    public function store()
    {
        $myts   = MyTextSanitizer::getInstance();
        $title  = '';
        $imgurl = '';
        if (isset($this->topic_title) && '' !== $this->topic_title) {
            $title = $GLOBALS['xoopsDB']->escape($this->topic_title);
        }
        if (isset($this->topic_imgurl) && '' !== $this->topic_imgurl) {
            $imgurl = $GLOBALS['xoopsDB']->escape($this->topic_imgurl);
        }
        if (!isset($this->topic_pid) || !\is_numeric($this->topic_pid)) {
            $this->topic_pid = 0;
        }
        if (empty($this->topic_id)) {
            $this->topic_id = $this->db->genId($this->table . '_topic_id_seq');
            $sql            = \sprintf("INSERT INTO `%s` (topic_id, topic_pid, topic_imgurl, topic_title) VALUES (%u, %u, '%s', '%s')", $this->table, $this->topic_id, $this->topic_pid, $imgurl, $title);
        } else {
            $sql = \sprintf("UPDATE `%s` SET topic_pid = %u, topic_imgurl = '%s', topic_title = '%s' WHERE topic_id = %u", $this->table, $this->topic_pid, $imgurl, $title, $this->topic_id);
        }
        if (!$result = $this->db->query($sql)) {
            \trigger_error("Query Failed! SQL: $sql- Error: " . $this->db->error(), E_USER_ERROR);
        }
        if (true === $this->use_permission) {
            if (empty($this->topic_id)) {
                $this->topic_id = $this->db->getInsertId();
            }
            $xt            = new \XoopsTree($this->table, 'topic_id', 'topic_pid');
            $parent_topics = $xt->getAllParentId($this->topic_id);
            if (!empty($this->m_groups) && \is_array($this->m_groups)) {
                foreach ($this->m_groups as $m_g) {
                    $moderate_topics = XoopsPerms::getPermitted($this->mid, 'ModInTopic', $m_g);
                    $add             = true;
                    // only grant this permission when the group has this permission in all parent topics of the created topic
                    foreach ($parent_topics as $p_topic) {
                        if (!\in_array($p_topic, $moderate_topics, true)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('ModInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($m_g);
                    }
                }
            }
            if (!empty($this->s_groups) && \is_array($this->s_groups)) {
                foreach ($s_groups as $s_g) {
                    $submit_topics = XoopsPerms::getPermitted($this->mid, 'SubmitInTopic', $s_g);
                    $add           = true;
                    foreach ($parent_topics as $p_topic) {
                        if (!\in_array($p_topic, $submit_topics, true)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('SubmitInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($s_g);
                    }
                }
            }
            if (!empty($this->r_groups) && \is_array($this->r_groups)) {
                foreach ($r_groups as $r_g) {
                    $read_topics = XoopsPerms::getPermitted($this->mid, 'ReadInTopic', $r_g);
                    $add         = true;
                    foreach ($parent_topics as $p_topic) {
                        if (!\in_array($p_topic, $read_topics, true)) {
                            $add = false;
                            continue;
                        }
                    }
                    if (true === $add) {
                        $xp = new XoopsPerms();
                        $xp->setModuleId($this->mid);
                        $xp->setName('ReadInTopic');
                        $xp->setItemId($this->topic_id);
                        $xp->store();
                        $xp->addGroup($r_g);
                    }
                }
            }
        }

        return true;
    }

    public function delete(): void
    {
        $sql = \sprintf('DELETE FROM `%s` WHERE topic_id = %u', $this->table, $this->topic_id);
        $this->db->query($sql);
    }

    /**
     * @return int
     */
    public function topic_id()
    {
        return $this->topic_id;
    }

    public function topic_pid()
    {
        return $this->topic_pid;
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
            case 'E':
                $title = \htmlspecialchars($this->topic_title, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'P':
            case 'F':
                $title = \htmlspecialchars($this->topic_title, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $title;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function topic_imgurl($format = 'S')
    {
        $myts = MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
            case 'E':
                $imgurl = \htmlspecialchars($this->topic_imgurl, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'P':
            case 'F':
                $imgurl = \htmlspecialchars($this->topic_imgurl, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $imgurl;
    }

    /**
     * @return null
     */
    public function prefix()
    {
        if (isset($this->prefix)) {
            return $this->prefix;
        }

        return null;
    }

    /**
     * @return array
     */
    public function getFirstChildTopics()
    {
        $ret       = [];
        $xt        = new \XoopsTree($this->table, 'topic_id', 'topic_pid');
        $topic_arr = $xt->getFirstChild($this->topic_id, 'topic_title');
        if (\is_array($topic_arr) && \count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getAllChildTopics()
    {
        $ret       = [];
        $xt        = new \XoopsTree($this->table, 'topic_id', 'topic_pid');
        $topic_arr = $xt->getAllChild($this->topic_id, 'topic_title');
        if (\is_array($topic_arr) && \count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    /**
     * @return array
     */
    public function getChildTopicsTreeArray()
    {
        $ret       = [];
        $xt        = new \XoopsTree($this->table, 'topic_id', 'topic_pid');
        $topic_arr = $xt->getChildTreeArray($this->topic_id, 'topic_title');
        if (\is_array($topic_arr) && \count($topic_arr)) {
            foreach ($topic_arr as $topic) {
                $ret[] = new self($this->table, $topic);
            }
        }

        return $ret;
    }

    /**
     * @param int    $none
     * @param        $seltopic
     * @param string $selname
     * @param string $onchange
     */
    public function makeTopicSelBox($none = 0, $seltopic = -1, $selname = '', $onchange = ''): void
    {
        $xt = new \XoopsModules\News\ObjectTree($this->table, 'topic_id', 'topic_pid');
        if (-1 != $seltopic) {
            $xt->makeMySelBox('topic_title', 'topic_title', $seltopic, $none, $selname, $onchange);
        } elseif (!empty($this->topic_id)) {
            $xt->makeMySelBox('topic_title', 'topic_title', $this->topic_id, $none, $selname, $onchange);
        } else {
            $xt->makeMySelBox('topic_title', 'topic_title', 0, $none, $selname, $onchange);
        }
    }

    //generates nicely formatted linked path from the root id to a given id

    /**
     * @param $funcURL
     *
     * @return mixed
     */
    public function getNiceTopicPathFromId($funcURL)
    {
        $xt  = new \XoopsModules\News\ObjectTree($this->table, 'topic_id', 'topic_pid');
        $ret = $xt->getNicePathFromId($this->topic_id, 'topic_title', $funcURL);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function getAllChildTopicsId()
    {
        $xt  = new \XoopsModules\News\ObjectTree($this->table, 'topic_id', 'topic_pid');
        $ret = $xt->getAllChildId($this->topic_id, 'topic_title');

        return $ret;
    }

    /**
     * @return array
     */
    public function getTopicsList()
    {
        $ret    = [];
        $result = $this->db->query('SELECT topic_id, topic_pid, topic_title FROM ' . $this->table);
        if ($result) {
            $myts = MyTextSanitizer::getInstance();
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[$myrow['topic_id']] = [
                    'title' => \htmlspecialchars($myrow['topic_title'], \ENT_QUOTES | \ENT_HTML5),
                    'pid'   => $myrow['topic_pid'],
                ];
            }
        }

        return $ret;
    }

    /**
     * @param $pid
     * @param $title
     *
     * @return bool
     */
    public function topicExists($pid, $title)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->table . ' WHERE topic_pid = ' . (int)$pid . " AND topic_title = '" . \trim($title) . "'";
        $rs  = $this->db->query($sql);
        [$count] = $this->db->fetchRow($rs);
        if ($count > 0) {
            return true;
        }

        return false;
    }
}
