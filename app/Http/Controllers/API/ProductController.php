<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateProductRequest;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public $producRepo;

    public function __construct(ProductRepository $productRepo) {
        $this->producRepo = $productRepo;
    }


    public function index() {
        return $this->producRepo->all();
    }

    public function show($id) {
        return $this->producRepo->find($id);
    }

    public function store(CreateProductRequest $request) {

        return $this->producRepo->create($request->all());
    }

    public function update($id, CreateProductRequest $request) {
        return $this->producRepo->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->producRepo->destroy($id);
    }

}
