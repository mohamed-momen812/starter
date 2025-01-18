<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Traits\ApiTrait;
use App\Traits\HandelImageTrait;
use Illuminate\Http\Request;

class CartController extends Controller
{
    use ApiTrait, HandelImageTrait;

    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        // Check if the user is logged in
        $userId = auth()->id();

        // Check if the product is already in the cart
        $cartItem = Cart::where('user_id', $userId)
                        ->where('product_id', $validated['product_id'])
                        ->first();

        if ($cartItem) {
            $cartItem->quantity += $validated['quantity'];
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $validated['product_id'],
                'quantity' => $validated['quantity'],
            ]);
        }

        return $this->responseJsonSuccess([], 'Product added to cart successfully');
    }

    public function viewCart()
    {
        $userId = auth()->id();

        $cartItems = Cart::where('user_id', $userId)
                        ->with('product')
                        ->get();

        return $this->responseJsonSuccess(CartResource::collection($cartItems));
    }

    public function updateCart(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = auth()->id();

        $cartItem = Cart::where('user_id', $userId)
                        ->where('id', $id)
                        ->first();

        if (!$cartItem) {
            return $this->responseJsonError('Cart item not found', 404);
        }

        $cartItem->quantity = $validated['quantity'];
        $cartItem->save();

        return $this->responseJsonSuccess([], 'Cart item quantity updated successfully');
    }

    public function removeFromCart($id)
    {
        $userId = auth()->id();

        $cartItem = Cart::where('user_id', $userId)
                        ->where('id', $id)
                        ->first();

        if (!$cartItem) {
            return $this->responseJsonError('Cart item not found', 404);
        }

        $cartItem->delete();

        return $this->responseJsonSuccess([], 'Cart item removed successfully');
    }

}
