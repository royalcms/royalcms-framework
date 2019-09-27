<?php

namespace Royalcms\Component\Broadcasting;


class BroadcastServiceProvider extends \Illuminate\Broadcasting\BroadcastServiceProvider
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
            $loader->alias('Royalcms\Component\Broadcasting\BroadcastEvent', 'Illuminate\Broadcasting\BroadcastEvent');
            $loader->alias('Royalcms\Component\Broadcasting\BroadcastManager', 'Illuminate\Broadcasting\BroadcastManager');
            $loader->alias('Royalcms\Component\Broadcasting\BroadcastManager', 'Illuminate\Broadcasting\PrivateChannel');
            $loader->alias('Royalcms\Component\Broadcasting\Broadcasters\LogBroadcaster', 'Illuminate\Broadcasting\Broadcasters\LogBroadcaster');
            $loader->alias('Royalcms\Component\Broadcasting\Broadcasters\PusherBroadcaster', 'Illuminate\Broadcasting\Broadcasters\PusherBroadcaster');
            $loader->alias('Royalcms\Component\Broadcasting\Broadcasters\RedisBroadcaster', 'Illuminate\Broadcasting\Broadcasters\RedisBroadcaster');
        });
    }
}
