<?php

namespace Royalcms\Component\Cache\SpecialStores;


trait AppCache
{
    /**
     * @return \Royalcms\Component\Cache\Stores\AppCache
     */
    public static function app()
    {
        static $cache;

        if (!empty($cache)) {
            return $cache;
        }

        $cache = (new \Royalcms\Component\Cache\Stores\AppCache);

        return $cache;
    }
    
    /**
     * 快速设置APP缓存数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string|array $data
     * @param string $app
     */
    public static function app_cache_set($name, $data, $app, $expire = null)
    {
        return static::app()->setApp($app)->set($key, $data, $expire);
    }
    
    /**
     * 快速添加APP缓存数据，如果name已经存在，则返回false
     *
     * @since 3.4
     *
     * @param string $name
     * @param string|array $data
     * @param string $app
     */
    public static function app_cache_add($name, $data, $app, $expire = null)
    {
        return static::app()->setApp($app)->add($key, $data, $expire);
    }
    
    /**
     * 快速获取APP缓存数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string $app
     */
    public static function app_cache_get($name, $app)
    {
        return static::app()->setApp($app)->get($key);
    }
    
    /**
     * 快速删除APP缓存数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string $app
     */
    public static function app_cache_delete($name, $app)
    {
        return static::app()->setApp($app)->forget($key);
    }
    
}