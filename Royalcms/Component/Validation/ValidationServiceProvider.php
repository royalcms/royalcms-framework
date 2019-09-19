<?php

namespace Royalcms\Component\Validation;

class ValidationServiceProvider extends \Illuminate\Validation\ValidationServiceProvider
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
