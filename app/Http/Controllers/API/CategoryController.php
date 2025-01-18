<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Interfaces\CategoryRepositoryInterface;
use App\Traits\ApiTrait;
use App\Traits\HandelImageTrait;

class CategoryController extends Controller
{
    use ApiTrait, HandelImageTrait;

    public $CategoryRepo;

    public function __construct(CategoryRepositoryInterface $CategoryRepository) {
        $this->CategoryRepo = $CategoryRepository;
    }


    public function index()
    {
        $categories = $this->CategoryRepo->all();

        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));

            $categories = $categories->filter(function ($category) use ($name) {
                $nameMatch = strpos(strtolower($category->first_name), $name) !== false;
                return $nameMatch;
            });
        }

        if (!empty($categories)) return $this->dataPaginate( CategoryResource::collection($categories));
        return $this->responseJsonFailed("No categories here", 404);
    }

    public function store(CategoryRequest $request) {
        return $this->CategoryRepo->create($request->all());
    }

    public function show($id) {
        $category = $this->CategoryRepo->find($id);
        return $category ? $this->responseJsonSuccess(new CategoryResource($category)) : $this->responseJsonFailed('Category not found', 404);
    }

    public function update($id, CategoryRequest $request) {
        return $this->CategoryRepo->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->CategoryRepo->destroy($id);
    }

}
