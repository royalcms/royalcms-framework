<?php

namespace Royalcms\Component\View;

class ViewServiceProvider extends \Illuminate\View\ViewServiceProvider
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
            $loader->alias('Royalcms\Component\View\Compilers\BladeCompiler', 'Illuminate\View\Compilers\BladeCompiler');
            $loader->alias('Royalcms\Component\View\Compilers\Compiler', 'Illuminate\View\Compilers\Compiler');
            $loader->alias('Royalcms\Component\View\Compilers\CompilerInterface', 'Illuminate\View\Compilers\CompilerInterface');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesAuthorizations', 'Illuminate\View\Compilers\Concerns\CompilesAuthorizations');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesComments', 'Illuminate\View\Compilers\Concerns\CompilesComments');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesComponents', 'Illuminate\View\Compilers\Concerns\CompilesComponents');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesConditionals', 'Illuminate\View\Compilers\Concerns\CompilesConditionals');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesEchos', 'Illuminate\View\Compilers\Concerns\CompilesEchos');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesHelpers', 'Illuminate\View\Compilers\Concerns\CompilesHelpers');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesIncludes', 'Illuminate\View\Compilers\Concerns\CompilesIncludes');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesInjections', 'Illuminate\View\Compilers\Concerns\CompilesInjections');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesJson', 'Illuminate\View\Compilers\Concerns\CompilesJson');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesLayouts', 'Illuminate\View\Compilers\Concerns\CompilesLayouts');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesLoops', 'Illuminate\View\Compilers\Concerns\CompilesLoops');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesRawPhp', 'Illuminate\View\Compilers\Concerns\CompilesRawPhp');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesStacks', 'Illuminate\View\Compilers\Concerns\CompilesStacks');
            $loader->alias('Royalcms\Component\View\Compilers\Concerns\CompilesTranslations', 'Illuminate\View\Compilers\Concerns\CompilesTranslations');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesComponents', 'Illuminate\View\Concerns\ManagesComponents');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesEvents', 'Illuminate\View\Concerns\ManagesEvents');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesLayouts', 'Illuminate\View\Concerns\ManagesLayouts');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesLoops', 'Illuminate\View\Concerns\ManagesLoops');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesStacks', 'Illuminate\View\Concerns\ManagesStacks');
            $loader->alias('Royalcms\Component\View\Concerns\ManagesTranslations', 'Illuminate\View\Concerns\ManagesTranslations');
            $loader->alias('Royalcms\Component\View\Engines\CompilerEngine', 'Illuminate\View\Engines\CompilerEngine');
            $loader->alias('Royalcms\Component\View\Engines\Engine', 'Illuminate\View\Engines\Engine');
            $loader->alias('Royalcms\Component\View\Engines\EngineResolver', 'Illuminate\View\Engines\EngineResolver');
            $loader->alias('Royalcms\Component\View\Engines\FileEngine', 'Illuminate\View\Engines\FileEngine');
            $loader->alias('Royalcms\Component\View\Engines\PhpEngine', 'Illuminate\View\Engines\PhpEngine');
//            $loader->alias('Royalcms\Component\View\Expression', 'Illuminate\View\Expression');
            $loader->alias('Royalcms\Component\View\Factory', 'Illuminate\View\Factory');
            $loader->alias('Royalcms\Component\View\Middleware\ShareErrorsFromSession', 'Illuminate\View\Middleware\ShareErrorsFromSession');
            $loader->alias('Royalcms\Component\View\View', 'Illuminate\View\View');
            $loader->alias('Royalcms\Component\View\ViewName', 'Illuminate\View\ViewName');
        });
    }


}
