<?php

namespace Royalcms\Component\Validation;

class ValidationServiceProvider extends \Illuminate\Validation\ValidationServiceProvider
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
            $loader->alias('Royalcms\Component\Validation\DatabasePresenceVerifier', 'Illuminate\Validation\DatabasePresenceVerifier');
            $loader->alias('Royalcms\Component\Validation\Factory', 'Illuminate\Validation\Factory');
            $loader->alias('Royalcms\Component\Validation\PresenceVerifierInterface', 'Illuminate\Validation\PresenceVerifierInterface');
            $loader->alias('Royalcms\Component\Validation\ValidatesWhenResolvedTrait', 'Illuminate\Validation\ValidatesWhenResolvedTrait');
            $loader->alias('Royalcms\Component\Validation\Validator', 'Illuminate\Validation\Validator');
        });
    }


}
