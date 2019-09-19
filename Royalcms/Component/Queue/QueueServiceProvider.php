<?php

namespace Royalcms\Component\Queue;

use RoyalcmsQueueClosure;
use Royalcms\Component\Support\ServiceProvider;
use Royalcms\Component\Queue\Console\WorkCommand;
use Royalcms\Component\Queue\Console\ListenCommand;
use Royalcms\Component\Queue\Console\RestartCommand;
use Royalcms\Component\Queue\Connectors\SqsConnector;
use Royalcms\Component\Queue\Console\SubscribeCommand;
use Royalcms\Component\Queue\Connectors\NullConnector;
use Royalcms\Component\Queue\Connectors\SyncConnector;
use Royalcms\Component\Queue\Connectors\IronConnector;
use Royalcms\Component\Queue\Connectors\RedisConnector;
use Royalcms\Component\Queue\Failed\NullFailedJobProvider;
use Royalcms\Component\Queue\Connectors\DatabaseConnector;
use Royalcms\Component\Queue\Connectors\BeanstalkdConnector;
use Royalcms\Component\Queue\Failed\DatabaseFailedJobProvider;

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

//        $this->registerManager();
//
//        $this->registerWorker();
//
//        $this->registerListener();
//
//        $this->registerSubscriber();
//
//        $this->registerFailedJobServices();
//
//        $this->registerQueueClosure();
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


//    /**
//     * Register the queue manager.
//     *
//     * @return void
//     */
//    protected function registerManager()
//    {
//        $this->royalcms->singleton('queue', function ($royalcms) {
//            // Once we have an instance of the queue manager, we will register the various
//            // resolvers for the queue connectors. These connectors are responsible for
//            // creating the classes that accept queue configs and instantiate queues.
//            $manager = new QueueManager($royalcms);
//
//            $this->registerConnectors($manager);
//
//            return $manager;
//        });
//
//        $this->royalcms->singleton('queue.connection', function ($royalcms) {
//            return $royalcms['queue']->connection();
//        });
//    }
//
//    /**
//     * Register the queue worker.
//     *
//     * @return void
//     */
//    protected function registerWorker()
//    {
//        $this->registerWorkCommand();
//
//        $this->registerRestartCommand();
//
//        $this->royalcms->singleton('queue.worker', function ($royalcms) {
//            return new Worker($royalcms['queue'], $royalcms['queue.failer'], $royalcms['events']);
//        });
//    }
//
//    /**
//     * Register the queue worker console command.
//     *
//     * @return void
//     */
//    protected function registerWorkCommand()
//    {
//        $this->royalcms->singleton('command.queue.work', function ($royalcms) {
//            return new WorkCommand($royalcms['queue.worker']);
//        });
//
//        $this->commands('command.queue.work');
//    }
//
//    /**
//     * Register the queue listener.
//     *
//     * @return void
//     */
//    protected function registerListener()
//    {
//        $this->registerListenCommand();
//
//        $this->royalcms->singleton('queue.listener', function ($royalcms) {
//            return new Listener($royalcms->basePath());
//        });
//    }
//
//    /**
//     * Register the queue listener console command.
//     *
//     * @return void
//     */
//    protected function registerListenCommand()
//    {
//        $this->royalcms->singleton('command.queue.listen', function ($royalcms) {
//            return new ListenCommand($royalcms['queue.listener']);
//        });
//
//        $this->commands('command.queue.listen');
//    }
//
//    /**
//     * Register the queue restart console command.
//     *
//     * @return void
//     */
//    public function registerRestartCommand()
//    {
//        $this->royalcms->singleton('command.queue.restart', function () {
//            return new RestartCommand;
//        });
//
//        $this->commands('command.queue.restart');
//    }
//
//    /**
//     * Register the push queue subscribe command.
//     *
//     * @return void
//     */
//    protected function registerSubscriber()
//    {
//        $this->royalcms->singleton('command.queue.subscribe', function () {
//            return new SubscribeCommand;
//        });
//
//        $this->commands('command.queue.subscribe');
//    }
//
//    /**
//     * Register the connectors on the queue manager.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    public function registerConnectors($manager)
//    {
//        foreach (['Null', 'Sync', 'Database', 'Beanstalkd', 'Redis', 'Sqs', 'Iron'] as $connector) {
//            $this->{"register{$connector}Connector"}($manager);
//        }
//    }
//
//    /**
//     * Register the Null queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerNullConnector($manager)
//    {
//        $manager->addConnector('null', function () {
//            return new NullConnector;
//        });
//    }
//
//    /**
//     * Register the Sync queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerSyncConnector($manager)
//    {
//        $manager->addConnector('sync', function () {
//            return new SyncConnector;
//        });
//    }
//
//    /**
//     * Register the Beanstalkd queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerBeanstalkdConnector($manager)
//    {
//        $manager->addConnector('beanstalkd', function () {
//            return new BeanstalkdConnector;
//        });
//    }
//
//    /**
//     * Register the database queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerDatabaseConnector($manager)
//    {
//        $manager->addConnector('database', function () {
//            return new DatabaseConnector($this->royalcms['db']);
//        });
//    }
//
//    /**
//     * Register the Redis queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerRedisConnector($manager)
//    {
//        $royalcms = $this->royalcms;
//
//        $manager->addConnector('redis', function () use ($royalcms) {
//            return new RedisConnector($royalcms['redis']);
//        });
//    }
//
//    /**
//     * Register the Amazon SQS queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerSqsConnector($manager)
//    {
//        $manager->addConnector('sqs', function () {
//            return new SqsConnector;
//        });
//    }
//
//    /**
//     * Register the IronMQ queue connector.
//     *
//     * @param  \Royalcms\Component\Queue\QueueManager  $manager
//     * @return void
//     */
//    protected function registerIronConnector($manager)
//    {
//        $royalcms = $this->royalcms;
//
//        $manager->addConnector('iron', function () use ($royalcms) {
//            return new IronConnector($royalcms['encrypter'], $royalcms['request']);
//        });
//
//        $this->registerIronRequestBinder();
//    }
//
//    /**
//     * Register the request rebinding event for the Iron queue.
//     *
//     * @return void
//     */
//    protected function registerIronRequestBinder()
//    {
//        $this->royalcms->rebinding('request', function ($royalcms, $request) {
//            if ($royalcms['queue']->connected('iron')) {
//                $royalcms['queue']->connection('iron')->setRequest($request);
//            }
//        });
//    }
//
//    /**
//     * Register the failed job services.
//     *
//     * @return void
//     */
//    protected function registerFailedJobServices()
//    {
//        $this->royalcms->singleton('queue.failer', function ($royalcms) {
//            $config = $royalcms['config']['queue.failed'];
//
//            if (isset($config['table'])) {
//                return new DatabaseFailedJobProvider($royalcms['db'], $config['database'], $config['table']);
//            } else {
//                return new NullFailedJobProvider;
//            }
//        });
//    }
//
//    /**
//     * Register the Royalcms queued closure job.
//     *
//     * @return void
//     */
//    protected function registerQueueClosure()
//    {
//        $this->royalcms->singleton('RoyalcmsQueueClosure', function ($royalcms) {
//            return new RoyalcmsQueueClosure($royalcms['encrypter']);
//        });
//    }
//
//    /**
//     * Get the services provided by the provider.
//     *
//     * @return array
//     */
//    public function provides()
//    {
//        return [
//            'queue', 'queue.worker', 'queue.listener', 'queue.failer',
//            'command.queue.work', 'command.queue.listen', 'command.queue.restart',
//            'command.queue.subscribe', 'queue.connection',
//        ];
//    }
}
