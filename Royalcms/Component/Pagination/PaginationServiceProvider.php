<?php

namespace Royalcms\Component\Pagination;

class PaginationServiceProvider extends \Illuminate\Pagination\PaginationServiceProvider
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
        parent::register();

        $this->loadAlias();
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Pagination\AbstractPaginator', 'Illuminate\Pagination\AbstractPaginator');
            $loader->alias('Royalcms\Component\Pagination\LengthAwarePaginator', 'Illuminate\Pagination\LengthAwarePaginator');
            $loader->alias('Royalcms\Component\Pagination\Paginator', 'Illuminate\Pagination\Paginator');
            $loader->alias('Royalcms\Component\Pagination\UrlWindow', 'Illuminate\Pagination\UrlWindow');
        });
    }


}
