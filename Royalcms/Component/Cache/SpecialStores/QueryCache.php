<?php

namespace Royalcms\Component\Cache\SpecialStores;


trait QueryCache
{
    /**
     * @return \Royalcms\Component\Cache\Stores\QueryCache
     */
    public static function query()
    {
        static $cache;

        if (!empty($cache)) {
            return $cache;
        }

        $cache = (new \Royalcms\Component\Cache\Stores\QueryCache());

        return $cache;
    }

    /**
     * 设置查询缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function query_cache_set($name, $data, $expire = null)
    {
        return static::query()->set($key, $data, $expire);
    }
    
    /**
     * 获取查询缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function query_cache_get($name)
    {
        return static::query()->get($key);
    }
    
    /**
     * 删除查询缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function query_cache_delete($name)
    {
        return static::query()->forget($key);
    }
    
}