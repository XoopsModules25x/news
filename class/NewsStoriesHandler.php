<?php declare(strict_types=1);

namespace XoopsModules\News;

use XoopsDatabase;
use XoopsPersistableObjectHandler;

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
 * Class newsnews_storiesHandler
 */
class NewsStoriesHandler extends XoopsPersistableObjectHandler
{
    /**
     * @param null|\XoopsDatabase $db
     */
    public function __construct(?\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'news_stories', NewsStories::class, 'storyid', 'uid');
    }
}
