<?php

namespace Royalcms\Component\Container;

use Royalcms\Component\Support\ServiceProvider;

class ContainerServiceProvider extends ServiceProvider
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
        $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
        $loader->alias('Royalcms\Component\Container\Container', 'Illuminate\Container\Container');
        $loader->alias('Royalcms\Component\Container\ContextualBindingBuilder', 'Illuminate\Container\ContextualBindingBuilder');
    }


}
