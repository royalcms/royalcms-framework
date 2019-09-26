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
        $loader->alias('Royalcms\Component\Support\MessageBag', 'Illuminate\Support\MessageBag');
        $loader->alias('Royalcms\Component\Support\Fluent', 'Illuminate\Support\Fluent');
        $loader->alias('Royalcms\Component\Support\ViewErrorBag', 'Illuminate\Support\ViewErrorBag');
        $loader->alias('Royalcms\Component\Support\Pluralizer', 'Illuminate\Support\Pluralizer');
        $loader->alias('Royalcms\Component\Support\Optional', 'Illuminate\Support\Optional');
        $loader->alias('Royalcms\Component\Support\NamespacedItemResolver', 'Illuminate\Support\NamespacedItemResolver');
        $loader->alias('Royalcms\Component\Support\HtmlString', 'Illuminate\Support\HtmlString');
        $loader->alias('Royalcms\Component\Support\HigherOrderTapProxy', 'Illuminate\Support\HigherOrderTapProxy');
        $loader->alias('Royalcms\Component\Support\HigherOrderCollectionProxy', 'Illuminate\Support\HigherOrderCollectionProxy');
        $loader->alias('Royalcms\Component\Support\Carbon', 'Illuminate\Support\Carbon');
        $loader->alias('Royalcms\Component\Support\Traits\CapsuleManagerTrait', 'Illuminate\Support\Traits\CapsuleManagerTrait');
        $loader->alias('Royalcms\Component\Support\Traits\Macroable', 'Illuminate\Support\Traits\Macroable');

        $loader->alias('Royalcms\Component\Support\ClassLoader', 'Royalcms\Component\ClassLoader\ClassLoader');
    }


}
