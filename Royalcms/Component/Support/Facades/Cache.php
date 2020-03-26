<?php

namespace Royalcms\Component\Support\Facades;

use Royalcms\Component\Cache\Traits\CustomCacheScreenTrait;

/**
 * @see \Royalcms\Component\Cache\CacheManager
 * @see \Royalcms\Component\Cache\Repository
 */
class Cache extends Facade
{
    use CustomCacheScreenTrait;

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cache';
    }
}
