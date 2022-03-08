<?php declare(strict_types=1);

namespace XoopsModules\News;

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
 * @author         XOOPS Development Team
 */

// require_once XOOPS_ROOT_PATH . '/modules/news/class/Mimetype.php';

/**
 * Class Files
 */
class Files
{
    public $db;
    public $table;
    public $fileid;
    public $filerealname;
    public $storyid;
    public $date;
    public $mimetype;
    public $downloadname;
    public $counter;

    /**
     * @param $fileid
     */
    public function __construct($fileid = -1)
    {
        /** @var \XoopsMySQLDatabase $db */
        $this->db           = \XoopsDatabaseFactory::getDatabaseConnection();
        $this->table        = $this->db->prefix('news_stories_files');
        $this->storyid      = 0;
        $this->filerealname = '';
        $this->date         = 0;
        $this->mimetype     = '';
        $this->downloadname = 'downloadfile';
        $this->counter      = 0;
        if (\is_array($fileid)) {
            $this->makeFile($fileid);
        } elseif (-1 != $fileid) {
            $this->getFile((int)$fileid);
        }
    }

    /**
     * @param      $folder
     * @param      $filename
     * @param bool $trimname
     *
     * @return string
     */
    public function createUploadName($folder, $filename, $trimname = false)
    {
        $workingfolder = $folder;
        if ('/' !== \xoops_substr($workingfolder, mb_strlen($workingfolder) - 1, 1)) {
            $workingfolder .= '/';
        }
        $ext  = \basename($filename);
        $ext  = \explode('.', $ext);
        $ext  = '.' . $ext[\count($ext) - 1];
        $true = true;
        while ($true) {
            $ipbits = \explode('.', $_SERVER['REMOTE_ADDR']);
            [$usec, $sec] = \explode(' ', \microtime());

            $usec *= 65536;
            $sec  = ((int)$sec) & 0xFFFF;

            if ($trimname) {
                $uid = \sprintf('%06x%04x%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            } else {
                $uid = \sprintf('%08x-%04x-%04x', ($ipbits[0] << 24) | ($ipbits[1] << 16) | ($ipbits[2] << 8) | $ipbits[3], $sec, $usec);
            }
            if (!\file_exists($workingfolder . $uid . $ext)) {
                $true = false;
            }
        }

        return $uid . $ext;
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    public function giveMimetype($filename = '')
    {
        $cmimetype   = new Mimetype();
        $workingfile = $this->downloadname;
        if ('' !== \xoops_trim($filename)) {
            $workingfile = $filename;

            return $cmimetype->getType($workingfile);
        }

        return '';
    }

    /**
     * @param $storyid
     *
     * @return array
     */
    public function getAllbyStory($storyid)
    {
        $ret    = [];
        $sql    = 'SELECT * FROM ' . $this->table . ' WHERE storyid=' . (int)$storyid;
        $result = $this->db->query($sql);
        if ($result instanceof \mysqli_result) {
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                $ret[] = new self($myrow);
            }
        }

        return $ret;
    }

    /**
     * @param $id
     */
    public function getFile($id): void
    {
        $sql   = 'SELECT * FROM ' . $this->table . ' WHERE fileid=' . (int)$id;
        $array = $this->db->fetchArray($this->db->query($sql));
        $this->makeFile($array);
    }

    /**
     * @param $array
     */
    public function makeFile($array): void
    {
        foreach ($array as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return bool
     */
    public function store()
    {
        $myts         = \MyTextSanitizer::getInstance();
        $fileRealName = $GLOBALS['xoopsDB']->escape($this->filerealname);
        $downloadname = $GLOBALS['xoopsDB']->escape($this->downloadname);
        $date         = \time();
        $mimetype     = $GLOBALS['xoopsDB']->escape($this->mimetype);
        $counter      = (int)$this->counter;
        $storyid      = (int)$this->storyid;

        if (!isset($this->fileid)) {
            $newid        = (int)$this->db->genId($this->table . '_fileid_seq');
            $sql          = 'INSERT INTO ' . $this->table . ' (fileid, storyid, filerealname, date, mimetype, downloadname, counter) ' . 'VALUES (' . $newid . ',' . $storyid . ",'" . $fileRealName . "','" . $date . "','" . $mimetype . "','" . $downloadname . "'," . $counter . ')';
            $this->fileid = $newid;
        } else {
            $sql = 'UPDATE ' . $this->table . ' SET storyid=' . $storyid . ",filerealname='" . $fileRealName . "',date=" . $date . ",mimetype='" . $mimetype . "',downloadname='" . $downloadname . "',counter=" . $counter . ' WHERE fileid=' . $this->getFileid();
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }

        return true;
    }

    /**
     * @param string $workdir
     *
     * @return bool
     */
    public function delete($workdir = XOOPS_UPLOAD_PATH)
    {
        $sql = 'DELETE FROM ' . $this->table . ' WHERE fileid=' . $this->getFileid();
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (\is_file($workdir . '/' . $this->downloadname)) {
            \unlink($workdir . '/' . $this->downloadname);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function updateCounter()
    {
        $sql = 'UPDATE ' . $this->table . ' SET counter=counter+1 WHERE fileid=' . $this->getFileid();
        if (!$result = $this->db->queryF($sql)) {
            return false;
        }

        return true;
    }

    // ****************************************************************************************************************
    // All the Sets
    // ****************************************************************************************************************

    /**
     * @param $filename
     */
    public function setFileRealName($filename): void
    {
        $this->filerealname = $filename;
    }

    /**
     * @param $id
     */
    public function setStoryid($id): void
    {
        $this->storyid = (int)$id;
    }

    /**
     * @param $value
     */
    public function setMimetype($value): void
    {
        $this->mimetype = $value;
    }

    /**
     * @param $value
     */
    public function setDownloadname($value): void
    {
        $this->downloadname = $value;
    }

    // ****************************************************************************************************************
    // All the Gets
    // ****************************************************************************************************************

    /**
     * @return int
     */
    public function getFileid()
    {
        return (int)$this->fileid;
    }

    /**
     * @return int
     */
    public function getStoryid()
    {
        return (int)$this->storyid;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return (int)$this->counter;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return (int)$this->date;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getFileRealName($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
            case 'Show':
                $filerealname = \htmlspecialchars($this->filerealname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'E':
            case 'Edit':
                $filerealname = \htmlspecialchars($this->filerealname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'P':
            case 'Preview':
                $filerealname = \htmlspecialchars($this->filerealname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'F':
            case 'InForm':
                $filerealname = \htmlspecialchars($this->filerealname, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $filerealname;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getMimetype($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
            case 'Show':
                $filemimetype = \htmlspecialchars($this->mimetype, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'E':
            case 'Edit':
                $filemimetype = \htmlspecialchars($this->mimetype, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'P':
            case 'Preview':
                $filemimetype = \htmlspecialchars($this->mimetype, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'F':
            case 'InForm':
                $filemimetype = \htmlspecialchars($this->mimetype, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $filemimetype;
    }

    /**
     * @param string $format
     *
     * @return mixed
     */
    public function getDownloadname($format = 'S')
    {
        $myts = \MyTextSanitizer::getInstance();
        switch ($format) {
            case 'S':
            case 'Show':
                $filedownname = \htmlspecialchars($this->downloadname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'E':
            case 'Edit':
                $filedownname = \htmlspecialchars($this->downloadname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'P':
            case 'Preview':
                $filedownname = \htmlspecialchars($this->downloadname, \ENT_QUOTES | \ENT_HTML5);
                break;
            case 'F':
            case 'InForm':
                $filedownname = \htmlspecialchars($this->downloadname, \ENT_QUOTES | \ENT_HTML5);
                break;
        }

        return $filedownname;
    }

    // Deprecated

    /**
     * @param $storyid
     *
     * @return mixed
     */
    public function getCountbyStory($storyid)
    {
        $sql    = 'SELECT count(fileid) AS cnt FROM ' . $this->table . ' WHERE storyid=' . (int)$storyid . '';
        $result = $this->db->query($sql);
        $myrow  = $this->db->fetchArray($result);

        return $myrow['cnt'];
    }

    /**
     * @param $stories
     *
     * @return array
     */
    public function getCountbyStories($stories)
    {
        $ret = [];
        if (\count($stories) > 0) {
            $sql    = 'SELECT storyid, count(fileid) AS cnt FROM ' . $this->table . ' WHERE storyid IN (';
            $sql    .= \implode(',', $stories) . ') GROUP BY storyid';
            $result = $this->db->query($sql);
            if ($result instanceof \mysqli_result) {
                while (false !== ($myrow = $this->db->fetchArray($result))) {
                    $ret[$myrow['storyid']] = $myrow['cnt'];
                }
            }
        }

        return $ret;
    }
}
