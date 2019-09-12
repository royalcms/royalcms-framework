<?php

namespace Royalcms\Component\Filesystem;

class FilesystemServiceProvider extends \Illuminate\Support\ServiceProvider
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
            $loader->alias('Royalcms\Component\Filesystem\ClassFinder', 'Illuminate\Filesystem\ClassFinder');
            $loader->alias('Royalcms\Component\Filesystem\Filesystem', 'Illuminate\Filesystem\Filesystem');
            $loader->alias('Royalcms\Component\Filesystem\FilesystemAdapter', 'Illuminate\Filesystem\FilesystemAdapter');
            $loader->alias('Royalcms\Component\Filesystem\FilesystemManager', 'Illuminate\Filesystem\FilesystemManager');
        });
    }


}
