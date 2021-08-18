<?php

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
 * @copyright    XOOPS Project (https://xoops.org)
 * @license      GNU GPL 2 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author      XOOPS Development Team
 */

/**
 * Class Registryfile
 */
class Registryfile
{
    public $filename; // filename to manage

    /**
     * @param null $fichier
     */
    public function __construct($fichier = null)
    {
        $this->setfile($fichier);
    }

    /**
     * @param null $fichier
     */
    public function setfile($fichier = null)
    {
        if ($fichier) {
            $this->filename = XOOPS_UPLOAD_PATH . '/' . $fichier;
        }
    }

    /**
     * @param null $fichier
     *
     * @return bool|string
     */
    public function getfile($fichier = null)
    {
        $fw = '';
        if (!$fichier) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH . '/' . $fichier;
        }
        if (\file_exists($fw)) {
            return file_get_contents($fw);
        }

        return '';
    }

    /**
     * @param      $content
     * @param null $fichier
     *
     * @return bool
     */
    public function savefile($content, $fichier = null)
    {
        $fw = '';
        if (!$fichier) {
            $fw = $this->filename;
        } else {
            $fw = XOOPS_UPLOAD_PATH . '/' . $fichier;
        }
        if (\is_file($fw)) {
            @\unlink($fw);
        }
        $fp = \fopen($fw, 'wb') || exit(_ERRORS);
        \fwrite($fp, $content);
        \fclose($fp);

        return true;
    }
}
