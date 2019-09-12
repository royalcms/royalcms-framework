<?php

namespace Royalcms\Component\Cache;

class CacheServiceProvider extends \Illuminate\Cache\CacheServiceProvider
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
            $loader->alias('Royalcms\Component\Cache\ApcStore', 'Illuminate\Cache\ApcStore');
            $loader->alias('Royalcms\Component\Cache\ApcWrapper', 'Illuminate\Cache\ApcWrapper');
            $loader->alias('Royalcms\Component\Cache\ArrayStore', 'Illuminate\Cache\ArrayStore');
            $loader->alias('Royalcms\Component\Cache\CacheManager', 'Illuminate\Cache\CacheManager');
            $loader->alias('Royalcms\Component\Cache\Console\CacheTableCommand', 'Illuminate\Cache\Console\CacheTableCommand');
            $loader->alias('Royalcms\Component\Cache\Console\ClearCommand', 'Illuminate\Cache\Console\ClearCommand');
            $loader->alias('Royalcms\Component\Cache\DatabaseStore', 'Illuminate\Cache\DatabaseStore');
            $loader->alias('Royalcms\Component\Cache\FileStore', 'Illuminate\Cache\FileStore');
            $loader->alias('Royalcms\Component\Cache\MemcachedConnector', 'Illuminate\Cache\MemcachedConnector');
            $loader->alias('Royalcms\Component\Cache\MemcachedStore', 'Illuminate\Cache\MemcachedStore');
            $loader->alias('Royalcms\Component\Cache\NullStore', 'Illuminate\Cache\NullStore');
            $loader->alias('Royalcms\Component\Cache\RateLimiter', 'Illuminate\Cache\RateLimiter');
            $loader->alias('Royalcms\Component\Cache\RedisStore', 'Illuminate\Cache\RedisStore');
            $loader->alias('Royalcms\Component\Cache\RedisTaggedCache', 'Illuminate\Cache\RedisTaggedCache');
            $loader->alias('Royalcms\Component\Cache\Repository', 'Illuminate\Cache\Repository');
            $loader->alias('Royalcms\Component\Cache\TagSet', 'Illuminate\Cache\TagSet');
            $loader->alias('Royalcms\Component\Cache\TaggableStore', 'Illuminate\Cache\TaggableStore');
            $loader->alias('Royalcms\Component\Cache\TaggedCache', 'Illuminate\Cache\TaggedCache');
            $loader->alias('Royalcms\Component\Cache\WinCacheStore', 'Illuminate\Cache\WinCacheStore');
            $loader->alias('Royalcms\Component\Cache\XCacheStore', 'Illuminate\Cache\XCacheStore');
        });
    }


}
