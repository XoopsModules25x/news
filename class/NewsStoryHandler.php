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

//require_once XOOPS_ROOT_PATH . '/modules/news/class/xoopsstory.php';
//require XOOPS_ROOT_PATH . '/include/comment_constants.php';

require_once \dirname(__DIR__) . '/preloads/autoloader.php';

/** @var Helper $helper */
$helper = Helper::getInstance();
$helper->loadLanguage('main');

/**
 * Class news_NewsStoryHandler
 */
class NewsStoryHandler extends \XoopsPersistableObjectHandler
{
    /**
     * Constructor
     *
     * @param \XoopsDatabase|null $db database connection
     */
    public function __construct(?\XoopsDatabase $db = null)
    {
        parent::__construct($db, 'news_stories', NewsStory::class, 'storieid', 'title');
    }
}
