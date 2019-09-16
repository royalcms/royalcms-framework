<?php

namespace Royalcms\Component\Session;

use Royalcms\Component\Support\ServiceProvider;

class SessionServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->loadAlias();

        $this->setupDefaultDriver();

        $this->registerSessionManager();

        $this->registerSessionDriver();

        $this->royalcms->singleton('Royalcms\Component\Session\Middleware\StartSession');
    }

    /**
     * Setup the default session driver for the application.
     *
     * @return void
     */
    protected function setupDefaultDriver()
    {
        if ($this->royalcms->runningInConsole())
        {
            $this->royalcms['config']['session.driver'] = 'array';
        }
    }

    /**
     * Register the session manager instance.
     *
     * @return void
     */
    protected function registerSessionManager()
    {
        $this->royalcms->singleton('session', function ($royalcms) {
            return new SessionManager($royalcms);
        });
    }

    /**
     * Register the session driver instance.
     *
     * @return void
     */
    protected function registerSessionDriver()
    {
        $this->royalcms->singleton('session.store', function ($royalcms) {
            // First, we will create the session manager which is responsible for the
            // creation of the various session drivers when they are needed by the
            // application instance, and will resolve them on a lazy load basis.
            $manager = $royalcms['session'];

            return $manager->driver();
        });
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
        $loader->alias('Royalcms\Component\Session\CacheBasedSessionHandler', 'Illuminate\Session\CacheBasedSessionHandler');
        $loader->alias('Royalcms\Component\Session\CommandsServiceProvider', 'Illuminate\Session\CommandsServiceProvider');
        $loader->alias('Royalcms\Component\Session\Console\SessionTableCommand', 'Illuminate\Session\Console\SessionTableCommand');
        $loader->alias('Royalcms\Component\Session\CookieSessionHandler', 'Illuminate\Session\CookieSessionHandler');
        $loader->alias('Royalcms\Component\Session\DatabaseSessionHandler', 'Illuminate\Session\DatabaseSessionHandler');
        $loader->alias('Royalcms\Component\Session\EncryptedStore', 'Illuminate\Session\EncryptedStore');
        $loader->alias('Royalcms\Component\Session\ExistenceAwareInterface', 'Illuminate\Session\ExistenceAwareInterface');
        $loader->alias('Royalcms\Component\Session\FileSessionHandler', 'Illuminate\Session\FileSessionHandler');
        $loader->alias('Royalcms\Component\Session\Middleware\StartSession', 'Illuminate\Session\Middleware\StartSession');
        $loader->alias('Royalcms\Component\Session\SessionInterface', 'Illuminate\Session\SessionInterface');
        $loader->alias('Royalcms\Component\Session\SessionManager', 'Illuminate\Session\SessionManager');
        $loader->alias('Royalcms\Component\Session\Store', 'Illuminate\Session\Store');
//        $loader->alias('Royalcms\Component\Session\StoreInterface', 'Illuminate\Session\StoreInterface');
        $loader->alias('Royalcms\Component\Session\TokenMismatchException', 'Illuminate\Session\TokenMismatchException');
    }

}
