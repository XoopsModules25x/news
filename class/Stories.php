<?php
/**
 * ****************************************************************************
 * - Developers TEAM TDM Xoops - (https://xoops.org)
 * ****************************************************************************
 *       NEWS - MODULE FOR XOOPS
 *        Copyright (c) 2007 - 2011
 *       TXMod Xoops (http://www.txmodxoops.org)
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
 * @copyright       TXMod Xoops (http://www.txmodxoops.org)
 * @license         GPL see LICENSE
 * @package         news
 * @author          TXMod Xoops (info@txmodxoops.org)
 *
 * Version : 1.67 Tue 2012/02/13 22:29:36 : Timgno Exp $
 * ****************************************************************************
 */
// defined('XOOPS_ROOT_PATH') || exit('Restricted access.');

if (!class_exists('XoopsPersistableObjectHandler')) {
    require_once XOOPS_ROOT_PATH . '/kernel/object.php';
}

/**
 * Class news_stories
 */
class news_stories extends XoopsObject
{
    //Constructor
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->initVar('storyid', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('uid', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('created', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('published', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('expired', XOBJ_DTYPE_INT, null, false, 10);
        $this->initVar('hostname', XOBJ_DTYPE_TXTBOX, null, false, 150);
        $this->initVar('nohtml', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('nosmiley', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('hometext', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('bodytext', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('keywords', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('counter', XOBJ_DTYPE_INT, null, false, 8);
        $this->initVar('topicid', XOBJ_DTYPE_INT, null, false, 4);
        $this->initVar('ihome', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('notifypub', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('story_type', XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar('topicdisplay', XOBJ_DTYPE_INT, null, false, 1);
        $this->initVar('topicalign', XOBJ_DTYPE_TXTBOX, null, false, 1);
        $this->initVar('comments', XOBJ_DTYPE_INT, null, false, 5);
        $this->initVar('rating', XOBJ_DTYPE_DECIMAL, null, false, 6, 4);
        $this->initVar('votes', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('picture', XOBJ_DTYPE_TXTBOX, null, false, 50);
        $this->initVar('pictureinfo', XOBJ_DTYPE_TXTBOX, null, false, 255);
        $this->initVar('subtitle', XOBJ_DTYPE_TXTBOX, null, false, 255);
    }
}

/**
 * Class newsnews_storiesHandler
 */
class newsnews_storiesHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(\XoopsDatabase $db)
    {
        parent::__construct($db, 'news_stories', 'news_stories', 'storyid', 'uid');
    }
}
