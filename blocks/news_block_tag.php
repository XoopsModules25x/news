<?php declare(strict_types=1);
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
if (file_exists(XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php')) {
    /**
     * @param $options
     *
     * @return array
     */
    function news_tag_block_cloud_show($options)
    {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        $moduleDirName = 'news';

        return tag_block_cloud_show($options, $moduleDirName);
    }

    /**
     * @param $options
     *
     * @return string
     */
    function news_tag_block_cloud_edit($options)
    {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_cloud_edit($options);
    }

    /**
     * @param $options
     *
     * @return array
     */
    function news_tag_block_top_show($options)
    {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        $moduleDirName = 'news';

        return tag_block_top_show($options, $moduleDirName);
    }

    /**
     * @param $options
     *
     * @return string
     */
    function news_tag_block_top_edit($options)
    {
        require_once XOOPS_ROOT_PATH . '/modules/tag/blocks/block.php';

        return tag_block_top_edit($options);
    }
}
