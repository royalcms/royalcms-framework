<?php

namespace Royalcms\Component\Cache\Stores;

use RC_Config;

class QueryCache extends AbstractCache
{

    protected $name = 'query_cache';

    public function __construct()
    {
        parent::__construct();

        if (empty($this->config)) {
            $this->config = [
                'driver' => 'file',
                'path'   => storage_path().'/temp/query_caches',
                'expire' => 60, //分钟
            ];
            RC_Config::set('cache.stores.'.$name, $this->config);
        }
    }

    protected function buildCacheKey($name)
    {
        return $this->name . ':' . $name;;
    }

}