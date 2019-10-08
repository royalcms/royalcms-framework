<?php

namespace Royalcms\Component\Foundation\Providers;

use Royalcms\Component\Foundation\Composer;
use Royalcms\Component\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->royalcms->singleton('composer', function ($royalcms) {
            return new Composer($royalcms['files'], $royalcms->basePath());
        });

        $this->royalcms->alias('composer', Composer::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'composer',
            Composer::class
        ];
    }
}
