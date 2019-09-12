<?php

namespace Royalcms\Component\Database;

use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\DatabaseServiceProvider as LaravelDatabaseServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Queue\EntityResolver;
use Illuminate\Database\Eloquent\QueueEntityResolver;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Royalcms\Component\Database\Connectors\ConnectionFactory;

class DatabaseServiceProvider extends LaravelDatabaseServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->loadAlias();
    }

    /**
     * Register the primary database bindings.
     *
     * @return void
     */
    protected function registerConnectionServices()
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $this->app->singleton('db.factory', function ($app) {
            return new ConnectionFactory($app);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $this->app->singleton('db', function ($app) {
            return new DatabaseManager($app, $app['db.factory']);
        });

        $this->app->bind('db.connection', function ($app) {
            return $app['db']->connection();
        });
        
    }


    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Database\Capsule\Manager', 'Illuminate\Database\Capsule\Manager');
            $loader->alias('Royalcms\Component\Database\Connection', 'Illuminate\Database\Connection');
            $loader->alias('Royalcms\Component\Database\ConnectionInterface', 'Illuminate\Database\ConnectionInterface');
            $loader->alias('Royalcms\Component\Database\ConnectionResolver', 'Illuminate\Database\ConnectionResolver');
            $loader->alias('Royalcms\Component\Database\ConnectionResolverInterface', 'Illuminate\Database\ConnectionResolverInterface');
            $loader->alias('Royalcms\Component\Database\Connectors\Connector', 'Illuminate\Database\Connectors\Connector');
            $loader->alias('Royalcms\Component\Database\Connectors\ConnectorInterface', 'Illuminate\Database\Connectors\ConnectorInterface');
            $loader->alias('Royalcms\Component\Database\Connectors\MySqlConnector', 'Illuminate\Database\Connectors\MySqlConnector');
            $loader->alias('Royalcms\Component\Database\Connectors\PostgresConnector', 'Illuminate\Database\Connectors\PostgresConnector');
            $loader->alias('Royalcms\Component\Database\Connectors\SQLiteConnector', 'Illuminate\Database\Connectors\SQLiteConnector');
            $loader->alias('Royalcms\Component\Database\Connectors\SqlServerConnector', 'Illuminate\Database\Connectors\SqlServerConnector');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\BaseCommand', 'Illuminate\Database\Console\Migrations\BaseCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\InstallCommand', 'Illuminate\Database\Console\Migrations\InstallCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\MigrateCommand', 'Illuminate\Database\Console\Migrations\MigrateCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\MigrateMakeCommand', 'Illuminate\Database\Console\Migrations\MigrateMakeCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\RefreshCommand', 'Illuminate\Database\Console\Migrations\RefreshCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\ResetCommand', 'Illuminate\Database\Console\Migrations\ResetCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\RollbackCommand', 'Illuminate\Database\Console\Migrations\RollbackCommand');
            $loader->alias('Royalcms\Component\Database\Console\Migrations\StatusCommand', 'Illuminate\Database\Console\Migrations\StatusCommand');
            $loader->alias('Royalcms\Component\Database\Console\Seeds\SeedCommand', 'Illuminate\Database\Console\Seeds\SeedCommand');
            $loader->alias('Royalcms\Component\Database\Console\Seeds\StatusCommand', 'Illuminate\Database\Console\Seeds\SeederMakeCommand');
            $loader->alias('Royalcms\Component\Database\DatabaseManager', 'Illuminate\Database\DatabaseManager');
            $loader->alias('Royalcms\Component\Database\DetectsLostConnections', 'Illuminate\Database\DetectsLostConnections');
            $loader->alias('Royalcms\Component\Database\Eloquent\Collection', 'Illuminate\Database\Eloquent\Collection');
            $loader->alias('Royalcms\Component\Database\Eloquent\Factory', 'Illuminate\Database\Eloquent\Factory');
            $loader->alias('Royalcms\Component\Database\Eloquent\FactoryBuilder', 'Illuminate\Database\Eloquent\FactoryBuilder');
            $loader->alias('Royalcms\Component\Database\Eloquent\MassAssignmentException', 'Illuminate\Database\Eloquent\MassAssignmentException');
            $loader->alias('Royalcms\Component\Database\Eloquent\ModelNotFoundException', 'Illuminate\Database\Eloquent\ModelNotFoundException');
            $loader->alias('Royalcms\Component\Database\Eloquent\QueueEntityResolver', 'Illuminate\Database\Eloquent\QueueEntityResolver');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\BelongsTo', 'Illuminate\Database\Eloquent\Relations\BelongsTo');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\BelongsToMany', 'Illuminate\Database\Eloquent\Relations\BelongsToMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\HasMany', 'Illuminate\Database\Eloquent\Relations\HasMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\HasManyThrough', 'Illuminate\Database\Eloquent\Relations\HasManyThrough');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\HasOne', 'Illuminate\Database\Eloquent\Relations\HasOne');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\HasOneOrMany', 'Illuminate\Database\Eloquent\Relations\HasOneOrMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphMany', 'Illuminate\Database\Eloquent\Relations\MorphMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphOne', 'Illuminate\Database\Eloquent\Relations\MorphOne');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphOneOrMany', 'Illuminate\Database\Eloquent\Relations\MorphOneOrMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphPivot', 'Illuminate\Database\Eloquent\Relations\MorphPivot');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphTo', 'Illuminate\Database\Eloquent\Relations\MorphTo');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\MorphToMany', 'Illuminate\Database\Eloquent\Relations\MorphToMany');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\Pivot', 'Illuminate\Database\Eloquent\Relations\Pivot');
            $loader->alias('Royalcms\Component\Database\Eloquent\Relations\Relation', 'Illuminate\Database\Eloquent\Relations\Relation');
//            $loader->alias('Royalcms\Component\Database\Eloquent\ScopeInterface', 'Illuminate\Database\Eloquent\ScopeInterface');
            $loader->alias('Royalcms\Component\Database\Eloquent\SoftDeletes', 'Illuminate\Database\Eloquent\SoftDeletes');
            $loader->alias('Royalcms\Component\Database\Eloquent\SoftDeletingScope', 'Illuminate\Database\Eloquent\SoftDeletingScope');
            $loader->alias('Royalcms\Component\Database\Grammar', 'Illuminate\Database\Grammar');
            $loader->alias('Royalcms\Component\Database\MigrationServiceProvider', 'Illuminate\Database\MigrationServiceProvider');
            $loader->alias('Royalcms\Component\Database\Migrations\DatabaseMigrationRepository', 'Illuminate\Database\Migrations\DatabaseMigrationRepository');
            $loader->alias('Royalcms\Component\Database\Migrations\Migration', 'Illuminate\Database\Migrations\Migration');
            $loader->alias('Royalcms\Component\Database\Migrations\MigrationCreator', 'Illuminate\Database\Migrations\MigrationCreator');
            $loader->alias('Royalcms\Component\Database\Migrations\MigrationRepositoryInterface', 'Illuminate\Database\Migrations\MigrationRepositoryInterface');
            $loader->alias('Royalcms\Component\Database\Migrations\Migrator', 'Illuminate\Database\Migrations\Migrator');
            $loader->alias('Royalcms\Component\Database\PostgresConnection', 'Illuminate\Database\PostgresConnection');
            $loader->alias('Royalcms\Component\Database\QueryException', 'Illuminate\Database\QueryException');
            $loader->alias('Royalcms\Component\Database\Query\Expression', 'Illuminate\Database\Query\Expression');
            $loader->alias('Royalcms\Component\Database\Query\Grammars\Grammar', 'Illuminate\Database\Query\Grammars\Grammar');
            $loader->alias('Royalcms\Component\Database\Query\Grammars\MySqlGrammar', 'Illuminate\Database\Query\Grammars\MySqlGrammar');
            $loader->alias('Royalcms\Component\Database\Query\Grammars\PostgresGrammar', 'Illuminate\Database\Query\Grammars\PostgresGrammar');
            $loader->alias('Royalcms\Component\Database\Query\Grammars\SQLiteGrammar', 'Illuminate\Database\Query\Grammars\SQLiteGrammar');
            $loader->alias('Royalcms\Component\Database\Query\Grammars\SqlServerGrammar', 'Illuminate\Database\Query\Grammars\SqlServerGrammar');
            $loader->alias('Royalcms\Component\Database\Query\JoinClause', 'Illuminate\Database\Query\JoinClause');
            $loader->alias('Royalcms\Component\Database\Query\Processors\MySqlProcessor', 'Illuminate\Database\Query\Processors\MySqlProcessor');
            $loader->alias('Royalcms\Component\Database\Query\Processors\PostgresProcessor', 'Illuminate\Database\Query\Processors\PostgresProcessor');
            $loader->alias('Royalcms\Component\Database\Query\Processors\Processor', 'Illuminate\Database\Query\Processors\Processor');
            $loader->alias('Royalcms\Component\Database\Query\Processors\SQLiteProcessor', 'Illuminate\Database\Query\Processors\SQLiteProcessor');
            $loader->alias('Royalcms\Component\Database\Query\Processors\SqlServerProcessor', 'Illuminate\Database\Query\Processors\SqlServerProcessor');
            $loader->alias('Royalcms\Component\Database\SQLiteConnection', 'Illuminate\Database\SQLiteConnection');
            $loader->alias('Royalcms\Component\Database\Schema\Blueprint', 'Illuminate\Database\Schema\Blueprint');
            $loader->alias('Royalcms\Component\Database\Schema\Builder', 'Illuminate\Database\Schema\Builder');
            $loader->alias('Royalcms\Component\Database\Schema\Grammars\Grammar', 'Illuminate\Database\Schema\Grammars\Grammar');
            $loader->alias('Royalcms\Component\Database\Schema\Grammars\MySqlGrammar', 'Illuminate\Database\Schema\Grammars\MySqlGrammar');
            $loader->alias('Royalcms\Component\Database\Schema\Grammars\PostgresGrammar', 'Illuminate\Database\Schema\Grammars\PostgresGrammar');
            $loader->alias('Royalcms\Component\Database\Schema\Grammars\SQLiteGrammar', 'Illuminate\Database\Schema\Grammars\SQLiteGrammar');
            $loader->alias('Royalcms\Component\Database\Schema\Grammars\SqlServerGrammar', 'Illuminate\Database\Schema\Grammars\SqlServerGrammar');
            $loader->alias('Royalcms\Component\Database\Schema\MySqlBuilder', 'Illuminate\Database\Schema\MySqlBuilder');
            $loader->alias('Royalcms\Component\Database\Schema\PostgresBuilder', 'Illuminate\Database\Schema\PostgresBuilder');
            $loader->alias('Royalcms\Component\Database\Seeder', 'Illuminate\Database\Seeder');
            $loader->alias('Royalcms\Component\Database\SqlServerConnection', 'Illuminate\Database\SqlServerConnection');
        });
    }

}
