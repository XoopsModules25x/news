<?php namespace XoopsModules\News;

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
 * @license        {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package
 * @since
 * @author         XOOPS Development Team
 */

use XoopsModules\News;

// defined('XOOPS_ROOT_PATH') || die('Restricted access');


//require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopsstory.php';
require_once XOOPS_ROOT_PATH . '/include/comment_constants.php';

require_once dirname(__DIR__) . '/preloads/autoloader.php';

/** @var News\Helper $helper */
$helper = News\Helper::getInstance();
$helper->loadLanguage('main');


/**
 * Class news_NewsStoryHandler
 */
class NewsStoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * @param \XoopsDatabase|null $db
     */
    public function __construct(\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'news_stories', 'stories', 'storieid', 'title');
    }
}
