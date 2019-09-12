<?php

namespace Royalcms\Component\Support;

class SupportServiceProvider extends \Illuminate\Support\ServiceProvider
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
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Support\Collection', 'Illuminate\Support\Collection');
        });
    }


}
