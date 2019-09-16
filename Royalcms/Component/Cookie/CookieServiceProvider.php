<?php

namespace Royalcms\Component\Cookie;


class CookieServiceProvider extends \Illuminate\Cookie\CookieServiceProvider
{
    /**
     * The application instance.
     *
     * @var \Royalcms\Component\Contracts\Foundation\Royalcms
     */
    protected $royalcms;

    /**
     * Create a new service provider instance.
     *
     * @param  \Royalcms\Component\Contracts\Foundation\Royalcms  $royalcms
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
            $loader->alias('Royalcms\Component\Cookie\Middleware\AddQueuedCookiesToResponse', 'Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse');
            $loader->alias('Royalcms\Component\Cookie\Middleware\EncryptCookies', 'Illuminate\Cookie\Middleware\EncryptCookies');
            $loader->alias('Royalcms\Component\Cookie\CookieJar', 'Illuminate\Cookie\CookieJar');
        });
    }

}
