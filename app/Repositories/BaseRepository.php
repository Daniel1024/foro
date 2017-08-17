<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

abstract class BaseRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Model
     */
    abstract public function getModel();

    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function newQuery()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    public function tableQuery()
    {
        return DB::table($this->getModel()->getTable());
    }


    /**
     * @param $id
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function findOrFail($id)
    {
        return $this->newQuery()->findOrFail($id);
    }


    /**
     * Metodo create facilitado para su creación
     *
     * @param array $attributes
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function create(array $attributes)
    {
        return $this->getModel()->create($attributes);
    }
}
