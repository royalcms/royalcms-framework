<?php


namespace Royalcms\Component\Database\Eloquent;


use Illuminate\Database\Eloquent\Model as LaravelModel;

class Model extends LaravelModel
{

    public function __construct(array $attributes = [])
    {
        /**
         * 该模型是否被自动维护时间戳
         *
         * @var bool
         */
        $this->timestamps = false;

        parent::__construct($attributes);
    }

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new \Royalcms\Component\Database\Eloquent\Builder($query);
    }

    /**
     * Create a new Eloquent Collection instance.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new Collection($models);
    }
    
}