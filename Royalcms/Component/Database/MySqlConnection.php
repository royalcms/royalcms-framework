<?php


namespace Royalcms\Component\Database;

use Illuminate\Database\Connection as LaravelConnection;
use Royalcms\Component\Database\Query\Builder as QueryBuilder;

class Connection extends LaravelConnection
{
    /**
     * Get a new query builder instance.
     *
     * @return \Royalcms\Component\Database\Query\Builder
     */
    public function query()
    {
        return new QueryBuilder(
            $this, $this->getQueryGrammar(), $this->getPostProcessor()
        );
    }

}