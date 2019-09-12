<?php

namespace Royalcms\Component\Http;

class HttpServiceProvider extends \Illuminate\Support\ServiceProvider
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
//            $loader->alias('Royalcms\Component\Http\Exception\HttpResponseException', 'Illuminate\Http\Exception\HttpResponseException');
//            $loader->alias('Royalcms\Component\Http\Exception\PostTooLargeException', 'Illuminate\Http\Exception\PostTooLargeException');
            $loader->alias('Royalcms\Component\Http\File', 'Illuminate\Http\File');
            $loader->alias('Royalcms\Component\Http\FileHelpers', 'Illuminate\Http\FileHelpers');
            $loader->alias('Royalcms\Component\Http\JsonResponse', 'Illuminate\Http\JsonResponse');
            $loader->alias('Royalcms\Component\Http\Middleware\CheckResponseForModifications', 'Illuminate\Http\Middleware\CheckResponseForModifications');
            $loader->alias('Royalcms\Component\Http\Middleware\FrameGuard', 'Illuminate\Http\Middleware\FrameGuard');
            $loader->alias('Royalcms\Component\Http\RedirectResponse', 'Illuminate\Http\RedirectResponse');
            $loader->alias('Royalcms\Component\Http\Request', 'Illuminate\Http\Request');
            $loader->alias('Royalcms\Component\Http\Response', 'Illuminate\Http\Response');
            $loader->alias('Royalcms\Component\Http\ResponseTrait', 'Illuminate\Http\ResponseTrait');
            $loader->alias('Royalcms\Component\Http\Testing\File', 'Illuminate\Http\Testing\File');
            $loader->alias('Royalcms\Component\Http\Testing\FileFactory', 'Illuminate\Http\Testing\FileFactory');
            $loader->alias('Royalcms\Component\Http\Testing\MimeType', 'Illuminate\Http\Testing\MimeType');
            $loader->alias('Royalcms\Component\Http\UploadedFile', 'Illuminate\Http\UploadedFile');
        });
    }


}
