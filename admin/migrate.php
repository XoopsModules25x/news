<?php

declare(strict_types=1);
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * Migration for XOOPS modules
 *
 * @copyright      XOOPS Project  (https://xoops.org)
 * @license        GNU GPL 2.0 or later (https://www.gnu.org/licenses/gpl-2.0.html)
 * @author         Richard Griffith <richard@geekwright.com>
 * @author         Michael Beck <mambax7@gmail.com>
 */

use Xmf\Request;
use Xmf\Module\Admin;
use XoopsModules\News\{
    Common\Configurator,
    Common\Migrate,
    Helper
};

/** @var Admin $adminObject */
/** @var Configurator $configurator */
/** @var Migrate $migrator */

require __DIR__ . '/admin_header.php';
xoops_cp_header();

$adminObject->displayNavigation(basename(__FILE__));

echo <<<EOF
<form method="post" class="form-inline">
<div class="form-group">
<input name="show" class="btn btn-default" type="submit" value="Show SQL">
</div>
<div class="form-group">
<input name="migrate" class="btn btn-default" type="submit" value="Do Migration">
</div>
<div class="form-group">
<input name="schema" class="btn btn-default" type="submit" value="Write Schema">
</div>
</form>
EOF;

//XoopsLoad::load('migrate', 'newbb');

$configurator = new Configurator();

$migrator = new Migrate($configurator);

$op = Request::getCmd('op', 'show');
$opShow = Request::getCmd('show', '', 'POST');
$opMigrate = Request::getCmd('migrate', '', 'POST');
$opSchema = Request::getCmd('schema', '', 'POST');
$op = !empty($opShow) ? 'show' : $op;
$op = !empty($opMigrate) ? 'migrate' : $op;
$op = !empty($opSchema) ? 'schema' : $op;

$message = '';

switch ($op) {
    case 'show':
    default:
        $queue = $migrator->getSynchronizeDDL();
        if (!empty($queue)) {
            echo "<pre>\n";
            foreach ($queue as $line) {
                echo $line . ";\n";
            }
            echo "</pre>\n";
        }
        break;
    case 'migrate':
        $migrator->synchronizeSchema();
        $message = constant('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_OK');
        break;
    case 'schema':
        xoops_confirm(['op' => 'confirmwrite'], 'migrate.php', constant('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_WARNING'), constant('CO_' . $moduleDirNameUpper . '_' . 'CONFIRM'));
        break;
    case 'confirmwrite':
        if ($GLOBALS['xoopsSecurity']->check()) {
            $migrator->saveCurrentSchema();

            $message = constant('CO_' . $moduleDirNameUpper . '_' . 'MIGRATE_SCHEMA_OK');
        }
        break;
}

echo "<div>$message</div>";

require_once __DIR__ . '/admin_footer.php';
