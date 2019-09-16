<?php

namespace Royalcms\Component\Filesystem;

class FilesystemServiceProvider extends \Illuminate\Filesystem\FilesystemServiceProvider
{

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
        $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
//        $loader->alias('Royalcms\Component\Filesystem\ClassFinder', 'Illuminate\Filesystem\ClassFinder');
        $loader->alias('Royalcms\Component\Filesystem\Filesystem', 'Illuminate\Filesystem\Filesystem');
        $loader->alias('Royalcms\Component\Filesystem\FilesystemAdapter', 'Illuminate\Filesystem\FilesystemAdapter');
        $loader->alias('Royalcms\Component\Filesystem\FilesystemManager', 'Illuminate\Filesystem\FilesystemManager');
    }


}
