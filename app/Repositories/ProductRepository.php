<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Review;

class ProductRepository extends BaseRepository
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

        ProductDetail::where('product_id', $id)->delete();

        Review::where('product_id', $id)->delete();

        $product->imagable->delete();
    
        return parent::destroy($id);
    }


}
