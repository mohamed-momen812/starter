<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


interface BaseRepositoryInterface
{
    public function create(array $attributes): Model;
    public function update(array $attributes, $id);
    public function find($id): ?Model; // null if no modle found
    public function all();
    public function allWithPaginate($number = 10);
    public function allWithPaginateExcept($id, $number = 10);
    public function groupBy($key);
    public function destroy($id);
    public function findBy($field_name, $field);
}
