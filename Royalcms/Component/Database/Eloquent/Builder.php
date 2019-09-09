<?php


namespace Royalcms\Component\Database\Eloquent;


use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Builder extends QueryBuilder
{

    /**
     * Get the hydrated models without eager loading.
     *
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model[]|\Illuminate\Database\Eloquent\Builder[]
     */
    public function getModels($columns = ['*'])
    {
        return $this->model->hydrate(
            $this->query->get($columns)
        )->all();
    }

}