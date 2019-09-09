<?php


namespace Royalcms\Component\Database\Query;

use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Query\Grammars\Grammar;
use Illuminate\Database\Query\Processors\Processor;
use Illuminate\Support\Arr;

class Builder extends QueryBuilder
{

    /**
     * Execute the query as a "select" statement.
     *
     * @param  array|string  $columns
     * @return array
     */
    public function get($columns = ['*'])
    {
        return parent::get($columns)->all();
    }

}