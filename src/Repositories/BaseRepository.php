<?php

namespace Byancode\Repository\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    /**
     * The Model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * The Model class name.
     *
     * @var string
     */
    protected $modelClass;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        if (isset($this->modelClass)) {
            $this->model = app($this->modelClass);
        }
    }

    /**
     * Paginate the given query.
     *
     * @param int $n The number of models to return for pagination
     *
     * @return mixed
     */
    public function getPaginate($n)
    {
        return $this->model->paginate($n);
    }

    /**
     * Get all records.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Create a new model and return the instance.
     *
     * @param array $inputs
     *
     * @return Model
     */
    public function store(array $inputs)
    {
        return $this->model->create($inputs);
    }

    /**
     * FindOrFail Model and return the instance.
     *
     * @param int $id
     *
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getById($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update the model in the database.
     *
     * @param mixed $id
     * @param array $inputs
     * 
     * @return bool
     */
    public function update($id, array $inputs)
    {
        return $this->getById($id)->update($inputs);
    }

    /**
     * Delete the model from the database.
     *
     * @param int $id
     *
     * @throws \Exception
     * 
     * @return bool|null
     */
    public function destroy($id)
    {
        return $this->getById($id)->delete();
    }
}
