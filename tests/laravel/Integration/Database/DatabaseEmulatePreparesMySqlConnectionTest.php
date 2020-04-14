<?php

namespace Illuminate\Tests\Integration\Database;

use PDO;

class DatabaseEmulatePreparesMySqlConnectionTest extends DatabaseMySqlConnectionTest
{
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('app.debug', 'true');
        $app['config']->set('database.default', 'mysql');
        $app['config']->set('database.connections.mysql.options', [
            PDO::ATTR_EMULATE_PREPARES => true,
        ]);
    }
}
