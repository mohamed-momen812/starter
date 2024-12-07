<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $attributes): Model
    {
        $data = $this->model->create($attributes);

        return $data;
    }

    public function update(array $attributes, $id)
    {
        $this->model->where('id' , $id)->update($attributes);
        $data = $this->model->find($id);

        return $data;
    }

    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function allWithPaginate($number = 10){
        return $this->model->paginate($number);
    }

    public function allWithPaginateExcept($id, $number = 10){
        return $this->model->where('id', '!=',  $id)->paginate($number);
    }

    public function destroy($id)
    {
        $model = $this->model->findOrFail($id);

        return $model->delete();
    }

    public function groupBy($key){
        return $this->model->get()->groupBy($key)->toArray();
    }

    public function findBy($field_name, $field){
        $model = $this->model->where($field_name, $field)->first();

        return $model;
    }
}
