<?php declare(strict_types=1);

namespace XoopsModules\News;

use XoopsObject;

/**
 * ****************************************************************************
 * - Developers TEAM TDM Xoops - (https://xoops.org)
 * ****************************************************************************
 *       NEWS - MODULE FOR XOOPS
 *        Copyright (c) 2007 - 2011
 *       TXMod Xoops (https://www.txmodxoops.org)
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  You may not change or alter any portion of this comment or credits
 *  of supporting developers from this source code or any supporting
 *  source code which is considered copyrighted (c) material of the
 *  original comment or credit authors.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  ------------------------------------------------------------------------
 *
 * @copyright       TXMod Xoops (https://www.txmodxoops.org)
 * @license         GPL see LICENSE
 * @author          TXMod Xoops (info@txmodxoops.org)
 *
 * Version : 1.67 Tue 2012/02/13 22:29:36 : Timgno Exp $
 * ****************************************************************************
 */

/**
 * Class Topics
 */
class NewsTopics extends XoopsObject
{
    public $topic_id;
    public $topic_pid;
    public $topic_title;
    public $topic_imgurl;
    public $menu;
    public $topic_frontpage;
    public $topic_rssurl;
    public $topic_description;
    public $topic_color;

    //Constructor

    public function __construct()
    {
        parent::__construct();
        $this->initVar('topic_id', \XOBJ_DTYPE_INT, null, false, 4);
        $this->initVar('topic_pid', \XOBJ_DTYPE_INT, null, false, 4);
        $this->initVar('topic_title', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('topic_imgurl', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('menu', \XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('topic_frontpage', \XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('topic_rssurl', \XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('topic_description', \XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('topic_color', \XOBJ_DTYPE_TXTBOX, null, false);
    }
}
