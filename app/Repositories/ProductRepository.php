<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\Review;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{

    public function __construct(Product $model)
    {
        parent::__construct($model);
    }

    // overwrite destroy method to delete related data first and then call parent destroy
    public function destroy($id)
    {
        $product = $this->model->find($id);

        if (!$product) {
            throw new \Exception("Product not found");
        }

        Review::where('product_id', $id)->delete();
        $product->imagable->delete();

        return parent::destroy($id);
    }

}
