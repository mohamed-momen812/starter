<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{
    public $producRepo;

    public function __construct(ProductRepositoryInterface $productRepo) {
        $this->producRepo = $productRepo;
    }


    public function index() {
        return $this->producRepo->all();        
    }

    public function show($id) {
        return $this->producRepo->find($id);
    }

    public function store(ProductRequest $request) {

        return $this->producRepo->create($request->all());
    }

    public function update($id, ProductRequest $request) {
        return $this->producRepo->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->producRepo->destroy($id);
    }

}
