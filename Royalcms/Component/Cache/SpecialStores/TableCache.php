<?php

namespace Royalcms\Component\Cache\SpecialStores;


trait TableCache
{
    /**
     * @return \Royalcms\Component\Cache\Stores\TableCache
     */
    public static function table()
    {
        static $cache;

        if (!empty($cache)) {
            return $cache;
        }

        $cache = (new \Royalcms\Component\Cache\Stores\TableCache());

        return $cache;
    }

    /**
     * 设置数据表缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function table_cache_set($name, $data, $expire = null)
    {
        return static::table()->set($key, $data, $expire);
    }
    
    /**
     * 获取数据表缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function table_cache_get($name)
    {
        return static::table()->get($key);
    }
    
    /**
     * 删除数据表缓存
     *
     * @since 3.10
     *
     * @param string $name
     * @param string $value
     * @param string $expire
     * @return boolean
     */
    public static function table_cache_delete($name)
    {
        return static::table()->forget($key);
    }
    
}