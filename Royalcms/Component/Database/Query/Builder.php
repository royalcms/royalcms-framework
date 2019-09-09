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

    /**
     * Execute an aggregate function on the database.
     *
     * @param  string  $function
     * @param  array   $columns
     * @return mixed
     */
    public function aggregate($function, $columns = ['*'])
    {
        $results = $this->cloneWithout($this->unions ? [] : ['columns'])
            ->cloneWithoutBindings($this->unions ? [] : ['select'])
            ->setAggregate($function, $columns)
            ->get($columns);

        if (isset($results[0])) {
            return array_change_key_case((array) $results[0])['aggregate'];
        }
    }

}