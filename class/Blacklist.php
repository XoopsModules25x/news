<?php declare(strict_types=1);

namespace XoopsModules\News;

//  ------------------------------------------------------------------------ //
//                  Copyright (c) 2005-2006 Hervé Thouzard                     //
//                     <https://www.herve-thouzard.com>                      //
// ------------------------------------------------------------------------- //
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

/**
 * Class Blacklist
 */
class Blacklist
{
    public array $keywords; // Holds keywords

    /**
     * Get all the keywords
     */
    public function getAllKeywords(): array
    {
        $ret      = $tbl_black_list = [];
        $myts     = \MyTextSanitizer::getInstance();
        $filename = XOOPS_UPLOAD_PATH . '/news_black_list.php';
        if (\is_file($filename)) {
            require_once $filename;
            foreach ($tbl_black_list as $onekeyword) {
                if ('' !== \xoops_trim($onekeyword)) {
                    $onekeyword       = \htmlspecialchars($onekeyword, \ENT_QUOTES | \ENT_HTML5);
                    $ret[$onekeyword] = $onekeyword;
                }
            }
        }
        \asort($ret);
        $this->keywords = $ret;

        return $this->keywords;
    }

    /**
     * Remove one or many keywords from the list
     * @param $keyword
     */
    public function delete($keyword): void
    {
        if (\is_array($keyword)) {
            foreach ($keyword as $onekeyword) {
                if (isset($this->keywords[$onekeyword])) {
                    unset($this->keywords[$onekeyword]);
                }
            }
        } elseif (isset($this->keywords[$keyword])) {
            unset($this->keywords[$keyword]);
        }
    }

    /**
     * Add one or many keywords
     * @param $keyword
     */
    public function addkeywords($keyword): void
    {
        $myts = \MyTextSanitizer::getInstance();
        if (\is_array($keyword)) {
            foreach ($keyword as $onekeyword) {
                $onekeyword = \xoops_trim(\htmlspecialchars($onekeyword, \ENT_QUOTES | \ENT_HTML5));
                if ('' !== $onekeyword) {
                    $this->keywords[$onekeyword] = $onekeyword;
                }
            }
        } else {
            $keyword = \xoops_trim(\htmlspecialchars($keyword, \ENT_QUOTES | \ENT_HTML5));
            if ('' !== $keyword) {
                $this->keywords[$keyword] = $keyword;
            }
        }
    }

    /**
     * Remove, from a list, all the blacklisted words
     * @param $keywords
     * @return array
     */
    public function remove_blacklisted($keywords): array
    {
        $ret       = [];
        $tmp_array = \array_values($this->keywords);
        foreach ($keywords as $onekeyword) {
            $add = true;
            foreach ($tmp_array as $onebanned) {
                if (\preg_match('/' . $onebanned . '/i', $onekeyword)) {
                    $add = false;
                    break;
                }
            }
            if ($add) {
                $ret[] = $onekeyword;
            }
        }

        return $ret;
    }

    /**
     * Save keywords
     */
    public function store(): void
    {
        $filename = XOOPS_UPLOAD_PATH . '/news_black_list.php';
        if (\is_file($filename)) {
            \unlink($filename);
        }
        /** @var resource $fd */
        $fd = \fopen($filename, 'wb') || exit('Error unable to create the blacklist file');
        \fwrite($fd, "<?php\n");
        \fwrite($fd, '$tbl_black_list=array(' . "\n");
        foreach ($this->keywords as $onekeyword) {
            \fwrite($fd, '"' . $onekeyword . "\",\n");
        }
        \fwrite($fd, "'');\n");
        \fwrite($fd, "?>\n");
        \fclose($fd);
    }
}
