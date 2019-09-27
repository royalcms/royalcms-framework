<?php

namespace Royalcms\Component\Contracts;

use Royalcms\Component\Support\ServiceProvider;

class ContractsServiceProvider extends ServiceProvider
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
            $loader->alias('Royalcms\Component\Contracts\Auth\Access\Authorizable', 'Illuminate\Contracts\Auth\Access\Authorizable');
            $loader->alias('Royalcms\Component\Contracts\Auth\Access\Gate', 'Illuminate\Contracts\Auth\Access\Gate');
            $loader->alias('Royalcms\Component\Contracts\Auth\Authenticatable', 'Illuminate\Contracts\Auth\Authenticatable');
            $loader->alias('Royalcms\Component\Contracts\Auth\CanResetPassword', 'Illuminate\Contracts\Auth\CanResetPassword');
            $loader->alias('Royalcms\Component\Contracts\Auth\Guard', 'Illuminate\Contracts\Auth\Guard');
            $loader->alias('Royalcms\Component\Contracts\Auth\PasswordBroker', 'Illuminate\Contracts\Auth\PasswordBroker');
            $loader->alias('Royalcms\Component\Contracts\Auth\UserProvider', 'Illuminate\Contracts\Auth\UserProvider');

            $loader->alias('Royalcms\Component\Contracts\Broadcasting\Broadcaster', 'Illuminate\Contracts\Broadcasting\Broadcaster');
            $loader->alias('Royalcms\Component\Contracts\Broadcasting\Factory', 'Illuminate\Contracts\Broadcasting\Factory');
            $loader->alias('Royalcms\Component\Contracts\Broadcasting\ShouldBroadcast', 'Illuminate\Contracts\Broadcasting\ShouldBroadcast');
            $loader->alias('Royalcms\Component\Contracts\Broadcasting\ShouldBroadcastNow', 'Illuminate\Contracts\Broadcasting\ShouldBroadcastNow');

            $loader->alias('Royalcms\Component\Contracts\Bus\Dispatcher', 'Illuminate\Contracts\Bus\Dispatcher');
            $loader->alias('Royalcms\Component\Contracts\Bus\QueueingDispatcher', 'Illuminate\Contracts\Bus\QueueingDispatcher');

            $loader->alias('Royalcms\Component\Contracts\Cache\Factory', 'Illuminate\Contracts\Cache\Factory');
            $loader->alias('Royalcms\Component\Contracts\Cache\Repository', 'Illuminate\Contracts\Cache\Repository');
            $loader->alias('Royalcms\Component\Contracts\Cache\Store', 'Illuminate\Contracts\Cache\Store');

            $loader->alias('Royalcms\Component\Contracts\Config\Repository', 'Illuminate\Contracts\Config\Repository');

//            $loader->alias('Royalcms\Component\Contracts\Console\Application', 'Illuminate\Contracts\Console\Application');
//            $loader->alias('Royalcms\Component\Contracts\Console\Kernel', 'Illuminate\Contracts\Console\Kernel');

            $loader->alias('Royalcms\Component\Contracts\Container\ContextualBindingBuilder', 'Illuminate\Contracts\Container\ContextualBindingBuilder');

            $loader->alias('Royalcms\Component\Contracts\Cookie\Factory', 'Illuminate\Contracts\Cookie\Factory');
            $loader->alias('Royalcms\Component\Contracts\Cookie\QueueingFactory', 'Illuminate\Contracts\Cookie\QueueingFactory');

            $loader->alias('Royalcms\Component\Contracts\Database\ModelIdentifier', 'Illuminate\Contracts\Database\ModelIdentifier');

            $loader->alias('Royalcms\Component\Contracts\Debug\ExceptionHandler', 'Illuminate\Contracts\Debug\ExceptionHandler');

            $loader->alias('Royalcms\Component\Contracts\Encryption\DecryptException', 'Illuminate\Contracts\Encryption\DecryptException');
            $loader->alias('Royalcms\Component\Contracts\Encryption\Encrypter', 'Illuminate\Contracts\Encryption\Encrypter');
            $loader->alias('Royalcms\Component\Contracts\Encryption\EncryptException', 'Illuminate\Contracts\Encryption\EncryptException');

            $loader->alias('Royalcms\Component\Contracts\Filesystem\Cloud', 'Illuminate\Contracts\Filesystem\Cloud');
            $loader->alias('Royalcms\Component\Contracts\Filesystem\Factory', 'Illuminate\Contracts\Filesystem\Factory');
            $loader->alias('Royalcms\Component\Contracts\Filesystem\FileNotFoundException', 'Illuminate\Contracts\Filesystem\FileNotFoundException');
            $loader->alias('Royalcms\Component\Contracts\Filesystem\Filesystem', 'Illuminate\Contracts\Filesystem\Filesystem');

//            $loader->alias('Royalcms\Component\Contracts\Foundation\Application', 'Illuminate\Contracts\Foundation\Application');

            $loader->alias('Royalcms\Component\Contracts\Hashing\Hasher', 'Illuminate\Contracts\Hashing\Hasher');

//            $loader->alias('Royalcms\Component\Contracts\Http\Kernel', 'Illuminate\Contracts\Http\Kernel');

//            $loader->alias('Royalcms\Component\Contracts\Mail\Mailable', 'Illuminate\Contracts\Mail\Mailable');
//            $loader->alias('Royalcms\Component\Contracts\Mail\Mailer', 'Illuminate\Contracts\Mail\Mailer');
//            $loader->alias('Royalcms\Component\Contracts\Mail\MailQueue', 'Illuminate\Contracts\Mail\MailQueue');

            $loader->alias('Royalcms\Component\Contracts\Notifications\Dispatcher', 'Illuminate\Contracts\Notifications\Dispatcher');
            $loader->alias('Royalcms\Component\Contracts\Notifications\Factory', 'Illuminate\Contracts\Notifications\Factory');

            $loader->alias('Royalcms\Component\Contracts\Pagination\LengthAwarePaginator', 'Illuminate\Contracts\Pagination\LengthAwarePaginator');
            $loader->alias('Royalcms\Component\Contracts\Pagination\Paginator', 'Illuminate\Contracts\Pagination\Paginator');

            $loader->alias('Royalcms\Component\Contracts\Pipeline\Hub', 'Illuminate\Contracts\Pipeline\Hub');
            $loader->alias('Royalcms\Component\Contracts\Pipeline\Pipeline', 'Illuminate\Contracts\Pipeline\Pipeline');

            $loader->alias('Royalcms\Component\Contracts\Queue\EntityNotFoundException', 'Illuminate\Contracts\Queue\EntityNotFoundException');
            $loader->alias('Royalcms\Component\Contracts\Queue\EntityResolver', 'Illuminate\Contracts\Queue\EntityResolver');
            $loader->alias('Royalcms\Component\Contracts\Queue\Factory', 'Illuminate\Contracts\Queue\Factory');
            $loader->alias('Royalcms\Component\Contracts\Queue\Job', 'Illuminate\Contracts\Queue\Job');
            $loader->alias('Royalcms\Component\Contracts\Queue\Monitor', 'Illuminate\Contracts\Queue\Monitor');
            $loader->alias('Royalcms\Component\Contracts\Queue\Queue', 'Illuminate\Contracts\Queue\Queue');
            $loader->alias('Royalcms\Component\Contracts\Queue\QueueableEntity', 'Illuminate\Contracts\Queue\QueueableEntity');
            $loader->alias('Royalcms\Component\Contracts\Queue\ShouldQueue', 'Illuminate\Contracts\Queue\ShouldQueue');

            $loader->alias('Royalcms\Component\Contracts\Routing\UrlGenerator', 'Illuminate\Contracts\Routing\UrlGenerator');
            $loader->alias('Royalcms\Component\Contracts\Routing\UrlRoutable', 'Illuminate\Contracts\Routing\UrlRoutable');

            $loader->alias('Royalcms\Component\Contracts\Support\Arrayable', 'Illuminate\Contracts\Support\Arrayable');
            $loader->alias('Royalcms\Component\Contracts\Support\Htmlable', 'Illuminate\Contracts\Support\Htmlable');
            $loader->alias('Royalcms\Component\Contracts\Support\Jsonable', 'Illuminate\Contracts\Support\Jsonable');
            $loader->alias('Royalcms\Component\Contracts\Support\MessageBag', 'Illuminate\Contracts\Support\MessageBag');
            $loader->alias('Royalcms\Component\Contracts\Support\MessageProvider', 'Illuminate\Contracts\Support\MessageProvider');
            $loader->alias('Royalcms\Component\Contracts\Support\Renderable', 'Illuminate\Contracts\Support\Renderable');

            $loader->alias('Royalcms\Component\Contracts\Validation\Factory', 'Illuminate\Contracts\Validation\Factory');
            $loader->alias('Royalcms\Component\Contracts\Validation\ValidatesWhenResolved', 'Illuminate\Contracts\Validation\ValidatesWhenResolved');
            $loader->alias('Royalcms\Component\Contracts\Validation\Validator', 'Illuminate\Contracts\Validation\Validator');

            $loader->alias('Royalcms\Component\Contracts\View\Engine', 'Illuminate\Contracts\View\Engine');
            $loader->alias('Royalcms\Component\Contracts\View\View', 'Illuminate\Contracts\View\View');
        });
    }


}
