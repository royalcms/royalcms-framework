<?php

namespace Royalcms\Component\Pagination;

use Royalcms\Component\Support\ServiceProvider;

class PaginationServiceProvider extends \Illuminate\Pagination\PaginationServiceProvider
{

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
