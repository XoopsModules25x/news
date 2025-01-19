<?php declare(strict_types=1);

namespace XoopsModules\News\Common;

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

use RuntimeException;
use Xmf\Database\Tables;

/**
 * Class Migrate synchronize existing tables with target schema
 *
 * @category  Migrate
 * @author    Richard Griffith <richard@geekwright.com>
 * @copyright 2016 XOOPS Project (https://xoops.org)
 * @license   GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @link      https://xoops.org
 */
class Migrate extends \Xmf\Database\Migrate
{
    private $renameTables;

    /**
     * Migrate constructor.
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct()
    {
        $class = __NAMESPACE__ . '\\' . 'Configurator';
        if (!\class_exists($class)) {
            throw new RuntimeException("Class '$class' not found");
        }
        $configurator       = new $class();
        $this->renameTables = $configurator->renameTables;

        $moduleDirName = \basename(\dirname(__DIR__, 2));
        parent::__construct($moduleDirName);
    }


    /**
     * Change column size of a field
     *
     * @param string $tableName  table to convert
     * @param string $columnName column to convert
     * @param string $attribute  new attribute
     */
    private function changeColumnSize(string $tableName, string $columnName, string $attribute): void
    {
        $moduleDirName      = \basename(\dirname(__DIR__, 2));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);

        $tables = new Tables();
        if ($tables->useTable($tableName)) {
            $tables->alterColumn($tableName, $columnName, $attribute);
            if (!$tables->executeQueue()) {
                echo '<br>' . \constant('CO_' . $moduleDirNameUpper . '_' . 'UPGRADEFAILED4') . ' ' . $tables->getLastError();
            }
        }
    }


    /**
     * Perform any upfront actions before synchronizing the schema
     *
     * Some typical uses include
     *   table and column renames
     *   data conversions
     */
    protected function preSyncActions(): void
    {
       // change column size for IP address from varchar(16) to varchar(45) for IPv6
        $this->changeColumnSize('news_stories', 'hostname', 'varchar(45) NOT NULL DEFAULT \'\'');
        // change column size for Picture from varchar(50) to varchar(255) for SEO
        $this->changeColumnSize('news_stories', 'picture', 'varchar(255) NOT NULL DEFAULT \'\'');

    }
}
