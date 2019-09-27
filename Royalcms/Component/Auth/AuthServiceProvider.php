<?php

namespace Royalcms\Component\Auth;


class AuthServiceProvider extends \Illuminate\Auth\AuthServiceProvider
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
        $this->loadAlias();

        parent::register();
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Auth\GenericUser', 'Illuminate\Auth\GenericUser');
            $loader->alias('Royalcms\Component\Auth\DatabaseUserProvider', 'Illuminate\Auth\DatabaseUserProvider');
            $loader->alias('Royalcms\Component\Auth\EloquentUserProvider', 'Illuminate\Auth\EloquentUserProvider');
            $loader->alias('Royalcms\Component\Auth\GeneratorServiceProvider', 'Illuminate\Auth\GeneratorServiceProvider');
            $loader->alias('Royalcms\Component\Auth\AuthManager', 'Illuminate\Auth\AuthManager');
            $loader->alias('Royalcms\Component\Auth\Authenticatable', 'Illuminate\Auth\Authenticatable');
            $loader->alias('Royalcms\Component\Auth\Passwords\CanResetPassword', 'Illuminate\Auth\Passwords\CanResetPassword');
            $loader->alias('Royalcms\Component\Auth\Passwords\DatabaseTokenRepository', 'Illuminate\Auth\Passwords\DatabaseTokenRepository');
            $loader->alias('Royalcms\Component\Auth\Passwords\PasswordBroker', 'Illuminate\Auth\Passwords\PasswordBroker');
            $loader->alias('Royalcms\Component\Auth\Passwords\PasswordResetServiceProvider', 'Illuminate\Auth\Passwords\PasswordResetServiceProvider');
            $loader->alias('Royalcms\Component\Auth\Passwords\TokenRepositoryInterface', 'Illuminate\Auth\Passwords\TokenRepositoryInterface');
            $loader->alias('Royalcms\Component\Auth\Middleware\AuthenticateWithBasicAuth', 'Illuminate\Auth\Middleware\AuthenticateWithBasicAuth');
            $loader->alias('Royalcms\Component\Auth\Access\Gate', 'Illuminate\Auth\Access\Gate');
            $loader->alias('Royalcms\Component\Auth\Access\HandlesAuthorization', 'Illuminate\Auth\Access\HandlesAuthorization');
            $loader->alias('Royalcms\Component\Auth\Access\Response', 'Illuminate\Auth\Access\Response');
        });
    }

}
