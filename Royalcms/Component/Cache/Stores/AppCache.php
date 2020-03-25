<?php

namespace Royalcms\Component\Cache\Stores;


class AppCache extends AbstractCache
{

    protected $name = 'app_cache';

    protected $app;

    public function __construct()
    {
        parent::__construct();

        if (empty($this->config)) {
            $this->config = [
                'driver' => 'file',
                'path'   => storage_path().'/cache',
                'expire' => 60, //分钟
            ];
            RC_Config::set('cache.stores.'.$name, $this->config);
        }
    }

    protected function buildCacheKey($name)
    {
        if (!empty($this->app)) {
            $key = $this->app . ':' . $name;
        }
        else {
            $key = $name;
        }
        return $key;
    }

    /**
     * @return null
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param null $app
     * @return AppCache
     */
    public function setApp($app)
    {
        $this->app = $app;
        return $this;
    }



}