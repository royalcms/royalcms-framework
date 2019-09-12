<?php

namespace Royalcms\Component\Http;

class ValidationServiceProvider extends \Illuminate\Support\ServiceProvider
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
            $loader->alias('Royalcms\Component\Validation\DatabasePresenceVerifier', 'Illuminate\Validation\DatabasePresenceVerifier');
            $loader->alias('Royalcms\Component\Validation\Factory', 'Illuminate\Validation\Factory');
            $loader->alias('Royalcms\Component\Validation\PresenceVerifierInterface', 'Illuminate\Validation\PresenceVerifierInterface');
            $loader->alias('Royalcms\Component\Validation\ValidatesWhenResolvedTrait', 'Illuminate\Validation\ValidatesWhenResolvedTrait');
            $loader->alias('Royalcms\Component\Validation\Validator', 'Illuminate\Validation\Validator');
        });
    }


}
