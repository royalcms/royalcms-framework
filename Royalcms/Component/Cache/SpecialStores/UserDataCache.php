<?php

namespace Royalcms\Component\Cache\SpecialStores;


trait UserDataCache
{
    /**
     * @return \Royalcms\Component\Cache\Stores\UserdataCache
     */
    public static function userdata()
    {
        static $cache;

        if (!empty($cache)) {
            return $cache;
        }

        $cache = (new \Royalcms\Component\Cache\Stores\UserdataCache());

        return $cache;
    }

    /**
     * 快速存储用户个人数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string $data
     * @param string $userid
     * @param boolean $isadmin
     */
    public static function userdata_cache_set($name, $data, $userid, $isadmin = false, $expire = null)
    {
        return static::userdata()->setUserId($userid)->setUserType($isadmin)->set($key, $data, $expire);
    }
    
    /**
     * 快速读取用户个人数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string $userid
     * @param boolean $isadmin
     */
    public static function userdata_cache_get($name, $userid, $isadmin = false, $expire = null)
    {
        return static::userdata()->setUserId($userid)->setUserType($isadmin)->get($key);
    }
    
    /**
     * 快速删除用户个人数据
     *
     * @since 3.4
     *
     * @param string $name
     * @param string $userid
     * @param boolean $isadmin
     */
    public static function userdata_cache_delete($name, $userid, $isadmin = false, $expire = null)
    {
        return static::userdata()->setUserId($userid)->setUserType($isadmin)->forget($key);
    }
    
}