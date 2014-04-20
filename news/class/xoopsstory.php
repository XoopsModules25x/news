<?php
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
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: xoopsstory.php 8066 2011-11-06 05:09:33Z beckmi $
 * @deprecated
 */

if (!defined('XOOPS_ROOT_PATH')) {
    exit();
}
//$GLOBALS['xoopsLogger']->addDeprecated("'/class/xoopsstory.php' is deprecated since XOOPS 2.5.4, please create your own class instead.");
include_once XOOPS_ROOT_PATH."/modules/news/class/xoopstopic.php";
include_once XOOPS_ROOT_PATH."/kernel/user.php";

class MyXoopsStory
{
    var $table;
    var $storyid;
    var $topicid;
    var $uid;
    var $title;
    var $hometext;
    var $bodytext="";
    var $counter;
    var $created;
    var $published;
    var $expired;
    var $hostname;
    var $nohtml=0;
    var $nosmiley=0;
    var $ihome=0;
    var $notifypub=0;
    var $type;
    var $approved;
    var $topicdisplay;
    var $topicalign;
    var $db;
    var $topicstable;
    var $comments;

    function Story($storyid=-1)
    {
        $this->db =& XoopsDatabaseFactory::getDatabaseConnection();
        $this->table = "";
        $this->topicstable = "";
        if ( is_array($storyid) ) {
            $this->makeStory($storyid);
        } elseif ($storyid != -1) {
            $this->getStory(intval($storyid));
        }
    }

    function setStoryId($value)
    {
        $this->storyid = intval($value);
    }

    function setTopicId($value)
    {
        $this->topicid = intval($value);
    }

    function setUid($value)
    {
        $this->uid = intval($value);
    }

    function setTitle($value)
    {
        $this->title = $value;
    }

    function setHometext($value)
    {
        $this->hometext = $value;
    }

    function setBodytext($value)
    {
        $this->bodytext = $value;
    }

    function setPublished($value)
    {
        $this->published = intval($value);
    }

    function setExpired($value)
    {
        $this->expired = intval($value);
    }

    function setHostname($value)
    {
        $this->hostname = $value;
    }

    function setNohtml($value=0)
    {
        $this->nohtml = $value;
    }

    function setNosmiley($value=0)
    {
        $this->nosmiley = $value;
    }

    function setIhome($value)
    {
        $this->ihome = $value;
    }

    function setNotifyPub($value)
    {
        $this->notifypub = $value;
    }

    function setType($value)
    {
        $this->type = $value;
    }

    function setApproved($value)
    {
        $this->approved = intval($value);
    }

    function setTopicdisplay($value)
    {
        $this->topicdisplay = $value;
    }

    function setTopicalign($value)
    {
        $this->topicalign = $value;
    }

    function setComments($value)
    {
        $this->comments = intval($value);
    }

    function store($approved=false)
    {
        //$newpost = 0;
        $myts =& MyTextSanitizer::getInstance();
        $title =$myts->censorString($this->title);
        $hometext =$myts->censorString($this->hometext);
        $bodytext =$myts->censorString($this->bodytext);
        $title = $myts->addSlashes($title);
        $hometext = $myts->addSlashes($hometext);
        $bodytext = $myts->addSlashes($bodytext);
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
            $newstoryid = $this->db->genId($this->table."_storyid_seq");
            $created = time();
            $published = ($this->approved) ? $this->published : 0;

            $sql = sprintf("INSERT INTO %s (storyid, uid, title, created, published, expired, hostname, nohtml, nosmiley, hometext, bodytext, counter, topicid, ihome, notifypub, story_type, topicdisplay, topicalign, comments) VALUES (%u, %u, '%s', %u, %u, %u, '%s', %u, %u, '%s', '%s', %u, %u, %u, %u, '%s', %u, '%s', %u)", $this->table, $newstoryid, $this->uid, $title, $created, $published, $expired, $this->hostname, $this->nohtml, $this->nosmiley, $hometext, $bodytext, 0, $this->topicid, $this->ihome, $this->notifypub, $this->type, $this->topicdisplay, $this->topicalign, $this->comments);
        } else {
            if ($this->approved) {
                $sql = sprintf("UPDATE %s SET title = '%s', published = %u, expired = %u, nohtml = %u, nosmiley = %u, hometext = '%s', bodytext = '%s', topicid = %u, ihome = %u, topicdisplay = %u, topicalign = '%s', comments = %u WHERE storyid = %u", $this->table, $title, $this->published, $expired, $this->nohtml, $this->nosmiley, $hometext, $bodytext, $this->topicid, $this->ihome, $this->topicdisplay, $this->topicalign, $this->comments, $this->storyid);
            } else {
                $sql = sprintf("UPDATE %s SET title = '%s', expired = %u, nohtml = %u, nosmiley = %u, hometext = '%s', bodytext = '%s', topicid = %u, ihome = %u, topicdisplay = %u, topicalign = '%s', comments = %u WHERE storyid = %u", $this->table, $title, $expired, $this->nohtml, $this->nosmiley, $hometext, $bodytext, $this->topicid, $this->ihome, $this->topicdisplay, $this->topicalign, $this->comments, $this->storyid);
            }
            $newstoryid = $this->storyid;
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($newstoryid)) {
            $newstoryid = $this->db->getInsertId();
            $this->storyid = $newstoryid;
        }

        return $newstoryid;
    }

    function getStory($storyid)
    {
        $storyid = intval($storyid);
        $sql = "SELECT * FROM ".$this->table." WHERE storyid=" . $storyid . "";
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeStory($array);
    }

    function makeStory($array)
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    function delete()
    {
        $sql = sprintf("DELETE FROM %s WHERE storyid = %u", $this->table, $this->storyid);
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    function updateCounter()
    {
        $sql = sprintf("UPDATE %s SET counter = counter+1 WHERE storyid = %u", $this->table, $this->storyid);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    function updateComments($total)
    {
        $sql = sprintf("UPDATE %s SET comments = %u WHERE storyid = %u", $this->table, $total, $this->storyid);
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    function topicid()
    {
        return $this->topicid;
    }

    function topic()
    {
        return new MyXoopsTopic($this->topicstable, $this->topicid);
    }

    function uid()
    {
        return $this->uid;
    }

    function uname()
    {
        return XoopsUser::getUnameFromId($this->uid);
    }

    function title($format = "Show")
    {
        $myts =& MyTextSanitizer::getInstance();
        $smiley = 1;
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
        case "Show":
        case "Edit":
            $title = $myts->htmlSpecialChars($this->title);
            break;
        case "Preview":
        case "InForm":
            $title = $myts->htmlSpecialChars($myts->stripSlashesGPC($this->title));
            break;
        }

        return $title;
    }

    function hometext($format = "Show")
    {
        $myts =& MyTextSanitizer::getInstance();
        $html = 1;
        $smiley = 1;
        $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
        case "Show":
            $hometext = $myts->displayTarea($this->hometext, $html, $smiley, $xcodes);
            break;
        case "Edit":
            $hometext = htmlspecialchars($this->hometext, ENT_QUOTES);
            break;
        case "Preview":
            $hometext = $myts->previewTarea($this->hometext, $html, $smiley, $xcodes);
            break;
        case "InForm":
            $hometext = htmlspecialchars($myts->stripSlashesGPC($this->hometext), ENT_QUOTES);
            break;
        }

        return $hometext;
    }

    function bodytext($format = "Show")
    {
        $myts =& MyTextSanitizer::getInstance();
        $html = 1;
        $smiley = 1;
        $xcodes = 1;
        if ($this->nohtml()) {
            $html = 0;
        }
        if ($this->nosmiley()) {
            $smiley = 0;
        }
        switch ($format) {
        case "Show":
            $bodytext = $myts->displayTarea($this->bodytext, $html, $smiley, $xcodes);
            break;
        case "Edit":
            $bodytext = htmlspecialchars($this->bodytext, ENT_QUOTES);
            break;
        case "Preview":
            $bodytext = $myts->previewTarea($this->bodytext, $html, $smiley, $xcodes);
            break;
        case "InForm":
            $bodytext = htmlspecialchars($myts->stripSlashesGPC($this->bodytext), ENT_QUOTES);
            break;
        }

        return $bodytext;
    }

    function counter()
    {
        return $this->counter;
    }

    function created()
    {
        return $this->created;
    }

    function published()
    {
        return $this->published;
    }

    function expired()
    {
        return $this->expired;
    }

    function hostname()
    {
        return $this->hostname;
    }

    function storyid()
    {
        return $this->storyid;
    }

    function nohtml()
    {
        return $this->nohtml;
    }

    function nosmiley()
    {
        return $this->nosmiley;
    }

    function notifypub()
    {
        return $this->notifypub;
    }

    function type()
    {
        return $this->type;
    }

    function ihome()
    {
        return $this->ihome;
    }

    function topicdisplay()
    {
        return $this->topicdisplay;
    }

    function topicalign($astext = true)
    {
        if ($astext) {
            if ($this->topicalign == "R") {
                $ret = "right";
            } else {
                $ret = "left";
            }

            return $ret;
        }

        return $this->topicalign;
    }

    function comments()
    {
        return $this->comments;
    }
}
