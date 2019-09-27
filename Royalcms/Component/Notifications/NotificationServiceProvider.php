<?php

namespace Royalcms\Component\Notifications;


class NotificationServiceProvider extends \Illuminate\Notifications\NotificationServiceProvider
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

        $this->royalcms->alias(
            ChannelManager::class, 'notification'
        );
    }

    /**
     * Load the alias = One less install step for the user
     */
    protected function loadAlias()
    {
        $this->royalcms->booting(function() {
            $loader = \Royalcms\Component\Foundation\AliasLoader::getInstance();
            $loader->alias('Royalcms\Component\Notifications\SendQueuedNotifications', 'Illuminate\Notifications\SendQueuedNotifications');
            $loader->alias('Royalcms\Component\Notifications\RoutesNotifications', 'Illuminate\Notifications\RoutesNotifications');
            $loader->alias('Royalcms\Component\Notifications\Notification', 'Illuminate\Notifications\Notification');
            $loader->alias('Royalcms\Component\Notifications\Notifiable', 'Illuminate\Notifications\Notifiable');
            $loader->alias('Royalcms\Component\Notifications\HasDatabaseNotifications', 'Illuminate\Notifications\HasDatabaseNotifications');
            $loader->alias('Royalcms\Component\Notifications\DatabaseNotificationCollection', 'Illuminate\Notifications\DatabaseNotificationCollection');
            $loader->alias('Royalcms\Component\Notifications\DatabaseNotification', 'Illuminate\Notifications\DatabaseNotification');
            $loader->alias('Royalcms\Component\Notifications\ChannelManager', 'Illuminate\Notifications\ChannelManager');
            $loader->alias('Royalcms\Component\Notifications\Action', 'Illuminate\Notifications\Action');
            $loader->alias('Royalcms\Component\Notifications\Channels\BroadcastChannel', 'Illuminate\Notifications\Channels\BroadcastChannel');
            $loader->alias('Royalcms\Component\Notifications\Channels\DatabaseChannel', 'Illuminate\Notifications\Channels\DatabaseChannel');
            $loader->alias('Royalcms\Component\Notifications\Channels\MailChannel', 'Illuminate\Notifications\Channels\MailChannel');
            $loader->alias('Royalcms\Component\Notifications\Events\BroadcastNotificationCreated', 'Illuminate\Notifications\Events\BroadcastNotificationCreated');
            $loader->alias('Royalcms\Component\Notifications\Events\NotificationFailed', 'Illuminate\Notifications\Events\NotificationFailed');
            $loader->alias('Royalcms\Component\Notifications\Events\NotificationSending', 'Illuminate\Notifications\Events\NotificationSending');
            $loader->alias('Royalcms\Component\Notifications\Events\NotificationSent', 'Illuminate\Notifications\Events\NotificationSent');
            $loader->alias('Royalcms\Component\Notifications\Messages\BroadcastMessage', 'Illuminate\Notifications\Messages\BroadcastMessage');
            $loader->alias('Royalcms\Component\Notifications\Messages\DatabaseMessage', 'Illuminate\Notifications\Messages\DatabaseMessage');
            $loader->alias('Royalcms\Component\Notifications\Messages\MailMessage', 'Illuminate\Notifications\Messages\MailMessage');
            $loader->alias('Royalcms\Component\Notifications\Messages\SimpleMessage', 'Illuminate\Notifications\Messages\SimpleMessage');
        });
    }
    
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array(
            'notification',
            \Illuminate\Contracts\Notifications\Dispatcher::class,
            \Illuminate\Contracts\Notifications\Factory::class
        );
    }
}
