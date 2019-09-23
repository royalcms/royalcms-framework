<?php

namespace Royalcms\Component\Support;

use Illuminate\Support\Collection;

class SupportServiceProvider extends \Illuminate\Support\ServiceProvider
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
     * @param  \Royalcms\Component\Contracts\Foundation\Royalcms|\Illuminate\Contracts\Foundation\Application  $royalcms
     * @return void
     */
    public function __construct($royalcms)
    {
        parent::__construct($royalcms);

        $this->royalcms = $royalcms;
    }


    public function boot()
    {

        Collection::mixin(new \Royalcms\Component\Support\Mixins\CollectionMixin());

    }

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
        $loader->alias('Royalcms\Component\Support\Collection', 'Illuminate\Support\Collection');
    }


}
