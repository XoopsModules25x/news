<?php

//declare(strict_types=1);

namespace XoopsModules\News;

/**
 * XOOPS news story
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

//$GLOBALS['xoopsLogger']->addDeprecated("'/class/xoopsstory.php' is deprecated since XOOPS 2.5.4, please create your own class instead.");
// require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopstopic.php';
require_once XOOPS_ROOT_PATH . '/kernel/user.php';

/**
 * Class XoopsStory
 */
class XoopsStory
{
    public string $table;
    public int    $storyid;
    public int    $topicid;
    public int    $uid;
    public string $title;
    public string $hometext;
    public string $bodytext  = '';
    public int    $counter;
    public int    $created;
    public int    $published = 0;
    public int    $expired;
    public string $hostname;
    public int    $nohtml    = 0;
    public int    $nosmiley  = 0;
    public int    $ihome     = 0;
    public int    $notifypub = 0;
    public string $type;
    public bool   $approved;
    public int    $topicdisplay;
    public string $topicalign;
    public $db;
    public $topicstable;
    public $comments;

    public $img_name;
    public $menu;
    public $story_type;
    public $topic_color;
    public $topic_description;
    public $topic_frontpage;
    public $topic_id;
    public $topic_pid;
    public $topic_rssurl;


    /**
     * @param array|int $storyid
     */
    public function Story($storyid = -1): void
    {
        /** @var \XoopsMySQLDatabase $this ->db */
        $this->db          = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table       = '';
        $this->topicstable = '';
        if (\is_array($storyid)) {
            $this->makeStory($storyid);
        } elseif (-1 != $storyid) {
            $this->getStory((int)$storyid);
        }
    }

    /**
     * @param $value
     */
    public function setStoryId($value): void
    {
        $this->storyid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setTopicId($value): void
    {
        $this->topicid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setUid($value): void
    {
        $this->uid = (int)$value;
    }

    /**
     * @param $value
     */
    public function setTitle($value): void
    {
        $this->title = $value;
    }

    /**
     * @param $value
     */
    public function setHometext($value): void
    {
        $this->hometext = $value;
    }

    /**
     * @param $value
     */
    public function setBodytext($value): void
    {
        $this->bodytext = $value;
    }

    /**
     * @param $value
     */
    public function setPublished($value): void
    {
        $this->published = (int)$value;
    }

    /**
     * @param $value
     */
    public function setExpired($value): void
    {
        $this->expired = (int)$value;
    }

    /**
     * @param $value
     */
    public function setHostname($value): void
    {
        $this->hostname = $value;
    }

    /**
     * @param int $value
     */
    public function setNohtml(int $value = 0): void
    {
        $this->nohtml = $value;
    }

    /**
     * @param int $value
     */
    public function setNosmiley(int $value = 0): void
    {
        $this->nosmiley = $value;
    }

    /**
     * @param $value
     */
    public function setIhome($value): void
    {
        $this->ihome = $value;
    }

    /**
     * @param $value
     */
    public function setNotifyPub($value): void
    {
        $this->notifypub = $value;
    }

    /**
     * @param $value
     */
    public function setType($value): void
    {
        $this->type = $value;
    }

    /**
     * @param $value
     */
    public function setApproved($value): void
    {
        $this->approved = (int)$value;
    }

    /**
     * @param $value
     */
    public function setTopicdisplay($value): void
    {
        $this->topicdisplay = $value;
    }

    /**
     * @param $value
     */
    public function setTopicalign($value): void
    {
        $this->topicalign = $value;
    }

    /**
     * @param $value
     */
    public function setComments($value): void
    {
        $this->comments = (int)$value;
    }

    /**
     * @param bool $approved
     *
     * @return bool|int
     */
    public function store(bool $approved = false)
    {
        //$newpost = 0;
        $myts     = \MyTextSanitizer::getInstance();
        $title    = $myts->executeExtension('censor', $this->title);
        $hometext = $myts->executeExtension('censor', $this->hometext);
        $bodytext = $myts->executeExtension('censor', $this->bodytext);
        $title    = $GLOBALS['xoopsDB']->escape($title);
        $hometext = $GLOBALS['xoopsDB']->escape($hometext);
        $bodytext = $GLOBALS['xoopsDB']->escape($bodytext);
        if (!isset($this->nohtml) || 1 != $this->nohtml) {
            $this->nohtml = 0;
        }
        if (!isset($this->nosmiley) || 1 != $this->nosmiley) {
            $this->nosmiley = 0;
        }
        if (!isset($this->notifypub) || 1 != $this->notifypub) {
            $this->notifypub = 0;
        }
        if (!isset($this->topicdisplay) || 0 != $this->topicdisplay) {
            $this->topicdisplay = 1;
        }
        $expired = !empty($this->expired) ? $this->expired : 0;
        if (isset($this->storyid)) {
            if ($this->approved) {
                $sql = \sprintf(
                    "UPDATE `%s` SET title = '%s', published = %u, expired = %u, nohtml = %u, nosmiley = %u, hometext = '%s', bodytext = '%s', topicid = %u, ihome = %u, topicdisplay = %u, topicalign = '%s', comments = %u WHERE storyid = %u",
                    $this->table,
                    $title,
                    $this->published,
                    $expired,
                    $this->nohtml,
                    $this->nosmiley,
                    $hometext,
                    $bodytext,
                    $this->topicid,
                    $this->ihome,
                    $this->topicdisplay,
                    $this->topicalign,
                    $this->comments,
                    $this->storyid
                );
            } else {
                $sql = \sprintf(
                    "UPDATE `%s` SET title = '%s', expired = %u, nohtml = %u, nosmiley = %u, hometext = '%s', bodytext = '%s', topicid = %u, ihome = %u, topicdisplay = %u, topicalign = '%s', comments = %u WHERE storyid = %u",
                    $this->table,
                    $title,
                    $expired,
                    $this->nohtml,
                    $this->nosmiley,
                    $hometext,
                    $bodytext,
                    $this->topicid,
                    $this->ihome,
                    $this->topicdisplay,
                    $this->topicalign,
                    $this->comments,
                    $this->storyid
                );
            }
            $newstoryid = $this->storyid;
        } else {
            //$newpost = 1;
            $newstoryid = $this->db->genId($this->table . '_storyid_seq');
            $created    = \time();
            $published  = $this->approved ? $this->published : 0;

            $sql = \sprintf(
                "INSERT INTO `%s` (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments) VALUES (%u, %u, '%s', %u, %u, %u, '%s', %u, %u, '%s', '%s', %u, %u, %u, %u, '%s', %u, '%s', %u)",
                $this->table,
                $newstoryid,
                $this->uid,
                $title,
                $created,
                $published,
                $expired,
                $this->hostname,
                $this->nohtml,
                $this->nosmiley,
                $hometext,
                $bodytext,
                0,
                $this->topicid,
                $this->ihome,
                $this->notifypub,
                $this->type,
                $this->topicdisplay,
                $this->topicalign,
                $this->comments
            );
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($newstoryid)) {
            $newstoryid    = $this->db->getInsertId();
            $this->storyid = $newstoryid;
        }

        return $newstoryid;
    }

    /**
     * @param $storyid
     */
    public function getStory($storyid): void
    {
        $storyid = (int)$storyid;
        $sql     = 'SELECT * FROM ' . $this->table . ' WHERE storyid=' . $storyid;
        $array   = $this->db->fetchArray($this->db->query($sql));
        $this->makeStory($array);
    }

    /**
     * @param array $array
     */
    public function makeStory(array $array): void
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }

        if (!array_key_exists('type', $array)) {
            $this->type = ''; // or some default value
        }
    }

    /**
     * @return bool
     */
    public function delete(): bool
    {
        $sql = \sprintf('DELETE FROM `%s` WHERE storyid = %u', $this->table, $this->storyid);
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function updateCounter(): bool
    {
        $sql = \sprintf('UPDATE `%s` SET counter = counter+1 WHERE storyid = %u', $this->table, $this->storyid);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param $total
     *
     * @return bool
     */
    public function updateComments($total): bool
    {
        $sql = \sprintf('UPDATE `%s` SET comments = %u WHERE storyid = %u', $this->table, $total, $this->storyid);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    public function topicid()
    {
        return $this->topicid;
    }

    /**
     * @return \XoopsModules\News\XoopsTopic
     */
    public function topic(): XoopsTopic
    {
        return new XoopsTopic($this->topicstable, $this->topicid);
    }

    public function uid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function uname(): string
    {
        return \XoopsUser::getUnameFromId($this->uid);
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function title(string $format = 'Show'): string
    {
        $myts   = \MyTextSanitizer::getInstance();
        $smiley = 1;
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
            case 'Show':
            case 'Edit':
                $title = \htmlspecialchars($this->title, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'Preview':
            case 'InForm':
                $title = \htmlspecialchars($this->title, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $title;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function hometext(string $format = 'Show'): string
    {
        $myts   = \MyTextSanitizer::getInstance();
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
                $hometext = $myts->displayTarea($this->hometext, $html, $smiley, $xcodes);
                break;
            case 'Edit':
                $hometext = \htmlspecialchars($this->hometext, \ENT_QUOTES | ENT_HTML5);
                break;
            case 'Preview':
                $hometext = $myts->previewTarea($this->hometext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $hometext = \htmlspecialchars($this->hometext, \ENT_QUOTES | ENT_HTML5);
                break;
        }

        return $hometext;
    }

    /**
     * @param string $format
     *
     * @return string
     */
    public function bodytext(string $format = 'Show'): string
    {
        $myts   = \MyTextSanitizer::getInstance();
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
                $bodytext = $myts->displayTarea($this->bodytext, $html, $smiley, $xcodes);
                break;
            case 'Edit':
                $bodytext = \htmlspecialchars($this->bodytext, \ENT_QUOTES | ENT_HTML5);
                break;
            case 'Preview':
                $bodytext = $myts->previewTarea($this->bodytext, $html, $smiley, $xcodes);
                break;
            case 'InForm':
                $bodytext = \htmlspecialchars($this->bodytext, \ENT_QUOTES | ENT_HTML5);
                break;
        }

        return $bodytext;
    }

    public function counter()
    {
        return $this->counter;
    }

    public function created()
    {
        return $this->created;
    }

    public function published()
    {
        return $this->published;
    }

    public function expired()
    {
        return $this->expired;
    }

    public function hostname()
    {
        return $this->hostname;
    }

    public function storyid()
    {
        return $this->storyid;
    }

    /**
     * @return string|int
     */
    public function nohtml()
    {
        return $this->nohtml;
    }

    /**
     * @return string|int
     */
    public function nosmiley()
    {
        return $this->nosmiley;
    }

    /**
     * @return int
     */
    public function notifypub(): int
    {
        return (int)$this->notifypub;
    }

    public function type()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function ihome(): int
    {
        return (int)$this->ihome;
    }

    public function topicdisplay()
    {
        return $this->topicdisplay;
    }

    /**
     * @param bool $astext
     *
     * @return string
     */
    public function topicalign(bool $astext = true): string
    {
        if ($astext) {
            if ('R' === $this->topicalign) {
                $ret = 'right';
            } else {
                $ret = 'left';
            }

            return $ret;
        }

        return $this->topicalign;
    }

    public function comments()
    {
        return $this->comments;
    }
}
