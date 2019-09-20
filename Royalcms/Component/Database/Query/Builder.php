<?php


namespace Royalcms\Component\Database\Query;

use Illuminate\Database\Concerns\BuildsQueries;
use Illuminate\Database\Query\Builder as QueryBuilder;

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

    /**
     * Execute the query and get the first result.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|object|static|null
     */
    public function first($columns = ['*'])
    {
        $results = $this->take(1)->get($columns);

        return count($results) > 0 ? reset($results) : null;
    }

}