<?php

namespace Royalcms\Component\Queue;


class QueueServiceProvider extends \Illuminate\Queue\QueueServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * The application instance.
     *
     * @var \Royalcms\Component\Contracts\Foundation\Royalcms
     */
    protected $royalcms;

    /**
     * Create a new service provider instance.
     *
     * @param  \Royalcms\Component\Contracts\Foundation\Royalcms|\Illuminate\Contracts\Foundation\Application  $royalcms
     * @return void
     */
    public function __construct($royalcms)
    {
        parent::__construct($royalcms);

        $this->royalcms = $royalcms;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAlias();

        parent::register();
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Queue\BeanstalkdQueue', 'Illuminate\Queue\BeanstalkdQueue');
            $loader->alias('Royalcms\Component\Queue\CallQueuedHandler', 'Illuminate\Queue\CallQueuedHandler');
            $loader->alias('Royalcms\Component\Queue\Capsule\Manager', 'Illuminate\Queue\Capsule\Manager');
            $loader->alias('Royalcms\Component\Queue\Connectors\BeanstalkdConnector', 'Illuminate\Queue\Connectors\BeanstalkdConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\ConnectorInterface', 'Illuminate\Queue\Connectors\ConnectorInterface');
            $loader->alias('Royalcms\Component\Queue\Connectors\DatabaseConnector', 'Illuminate\Queue\Connectors\DatabaseConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\IronConnector', 'Illuminate\Queue\Connectors\IronConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\NullConnector', 'Illuminate\Queue\Connectors\NullConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\RedisConnector', 'Illuminate\Queue\Connectors\RedisConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\SqsConnector', 'Illuminate\Queue\Connectors\SqsConnector');
            $loader->alias('Royalcms\Component\Queue\Connectors\SyncConnector', 'Illuminate\Queue\Connectors\SyncConnector');
//            $loader->alias('Royalcms\Component\Queue\ConsoleServiceProvider', 'Illuminate\Queue\ConsoleServiceProvider');
            $loader->alias('Royalcms\Component\Queue\Console\FailedTableCommand', 'Illuminate\Queue\Console\FailedTableCommand');
            $loader->alias('Royalcms\Component\Queue\Console\FlushFailedCommand', 'Illuminate\Queue\Console\FlushFailedCommand');
            $loader->alias('Royalcms\Component\Queue\Console\ForgetFailedCommand', 'Illuminate\Queue\Console\ForgetFailedCommand');
            $loader->alias('Royalcms\Component\Queue\Console\ListFailedCommand', 'Illuminate\Queue\Console\ListFailedCommand');
            $loader->alias('Royalcms\Component\Queue\Console\ListenCommand', 'Illuminate\Queue\Console\ListenCommand');
            $loader->alias('Royalcms\Component\Queue\Console\RestartCommand', 'Illuminate\Queue\Console\RestartCommand');
            $loader->alias('Royalcms\Component\Queue\Console\RetryCommand', 'Illuminate\Queue\Console\RetryCommand');
            $loader->alias('Royalcms\Component\Queue\Console\SubscribeCommand', 'Illuminate\Queue\Console\SubscribeCommand');
            $loader->alias('Royalcms\Component\Queue\Console\TableCommand', 'Illuminate\Queue\Console\TableCommand');
            $loader->alias('Royalcms\Component\Queue\Console\WorkCommand', 'Illuminate\Queue\Console\WorkCommand');
            $loader->alias('Royalcms\Component\Queue\DatabaseQueue', 'Illuminate\Queue\DatabaseQueue');
            $loader->alias('Royalcms\Component\Queue\Failed\DatabaseFailedJobProvider', 'Illuminate\Queue\Failed\DatabaseFailedJobProvider');
            $loader->alias('Royalcms\Component\Queue\Failed\FailedJobProviderInterface', 'Illuminate\Queue\Failed\FailedJobProviderInterface');
            $loader->alias('Royalcms\Component\Queue\Failed\NullFailedJobProvider', 'Illuminate\Queue\Failed\NullFailedJobProvider');
            $loader->alias('Royalcms\Component\Queue\InteractsWithQueue', 'Illuminate\Queue\InteractsWithQueue');
            $loader->alias('Royalcms\Component\Queue\IronQueue', 'Illuminate\Queue\IronQueue');
            $loader->alias('Royalcms\Component\Queue\Jobs\BeanstalkdJob', 'Illuminate\Queue\Jobs\BeanstalkdJob');
            $loader->alias('Royalcms\Component\Queue\Jobs\DatabaseJob', 'Illuminate\Queue\Jobs\DatabaseJob');
            $loader->alias('Royalcms\Component\Queue\Jobs\IronJob', 'Illuminate\Queue\Jobs\IronJob');
            $loader->alias('Royalcms\Component\Queue\Jobs\Job', 'Illuminate\Queue\Jobs\Job');
            $loader->alias('Royalcms\Component\Queue\Jobs\RedisJob', 'Illuminate\Queue\Jobs\RedisJob');
            $loader->alias('Royalcms\Component\Queue\Jobs\SqsJob', 'Illuminate\Queue\Jobs\SqsJob');
            $loader->alias('Royalcms\Component\Queue\Jobs\SyncJob', 'Illuminate\Queue\Jobs\SyncJob');
            $loader->alias('Royalcms\Component\Queue\Listener', 'Illuminate\Queue\Listener');
            $loader->alias('Royalcms\Component\Queue\NullQueue', 'Illuminate\Queue\NullQueue');
            $loader->alias('Royalcms\Component\Queue\Queue', 'Illuminate\Queue\Queue');
            $loader->alias('Royalcms\Component\Queue\QueueManager', 'Illuminate\Queue\QueueManager');
            $loader->alias('Royalcms\Component\Queue\RedisQueue', 'Illuminate\Queue\RedisQueue');
            $loader->alias('Royalcms\Component\Queue\SerializesAndRestoresModelIdentifiers', 'Illuminate\Queue\SerializesAndRestoresModelIdentifiers');
            $loader->alias('Royalcms\Component\Queue\SerializesModels', 'Illuminate\Queue\SerializesModels');
            $loader->alias('Royalcms\Component\Queue\SqsQueue', 'Illuminate\Queue\SqsQueue');
            $loader->alias('Royalcms\Component\Queue\SyncQueue', 'Illuminate\Queue\SyncQueue');
            $loader->alias('Royalcms\Component\Queue\Worker', 'Illuminate\Queue\Worker');
        });
    }

}
