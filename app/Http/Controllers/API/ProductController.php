<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Interfaces\ProductRepositoryInterface;
use App\Traits\ApiTrait;
use App\Traits\HandelImageTrait;

class ProductController extends Controller
{
    use ApiTrait, HandelImageTrait;

    public $productRepo;

    public function __construct(ProductRepositoryInterface $productRepository) {
        $this->productRepo = $productRepository;
    }


    public function index()
    {
        $products = $this->productRepo->all();

        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));

            $products = $products->filter(function ($product) use ($name) {
                $nameMatch = strpos(strtolower($product->first_name), $name) !== false;
                return $nameMatch;
            });
        }

        if (!empty($products)) return $this->dataPaginate( ProductResource::collection($products));
        return $this->responseJsonFailed("No products here", 404);
    }

    public function store(ProductRequest $request) {
        return $this->productRepo->create($request->all());
    }

    public function show($id) {
        $product = $this->productRepo->find($id);
        return $product ? $this->responseJsonSuccess(new ProductResource($product)) : $this->responseJsonFailed('Product not found', 404);
    }

    public function update($id, ProductRequest $request) {
        return $this->productRepo->update($id, $request->all());
    }

    public function destroy($id) {
        return $this->productRepo->destroy($id);
    }

}
