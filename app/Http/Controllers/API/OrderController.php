<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use ApiTrait;

    protected $paymentController;

    // Inject paymentController via the constructor
    public function __construct(PaymentController $paymentController)
    {
        $this->paymentController = $paymentController;
    }

    public function placeOrder()
    {
        $userId = auth()->id();

        // Fetch cart items for the user
        $cartItems = Cart::where('user_id', $userId)->get();

        if ($cartItems->isEmpty()) {
            return $this->responseJsonFailed( 'Your cart is empty.');
        }

        // Validate stock availability
        foreach ($cartItems as $item) {
            $product = Product::find($item->product_id);

            if (!$product) {
                return $this->responseJsonFailed( "Product with ID {$item->product_id} not found.");
            }

            if ($product->stock < $item->quantity) {
                return $this->responseJsonFailed( "Product {$product->name} is out of stock.");
            }
        }

        // Proceed to place the order (if stock validation passes)
        return $this->createOrder($cartItems);

    }

    protected function createOrder($cartItems)
    {
        // Begin transaction
        DB::beginTransaction();

        try {
            // Create the order
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => $cartItems->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                }),
                'status' => 'pending',
            ]);

            // Add items to the order and deduct stock
            foreach ($cartItems as $item) {
                $product = Product::find($item->product_id);

                // Deduct stock
                $product->decrement('stock', $item->quantity);

                // Add to order items
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                    'price' => $product->price,
                ]);
            }

            // Process payment
            $paymentDetails = $this->paymentController->pay($order->total);

            // Save Transaction Details
            if ($paymentDetails['status'] === 'completed') {
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $paymentDetails['transaction_id'],
                    'payment_method' => $paymentDetails['method'],
                    'amount' => $order->total,
                    'status' => 'completed',
                ]);

                // Update Order Status
                $order->update(['status' => 'completed']);
            } else {
                // Save failed transaction status
                Transaction::create([
                    'order_id' => $order->id,
                    'transaction_id' => $paymentDetails['transaction_id'],
                    'payment_method' => $paymentDetails['method'],
                    'amount' => $order->total,
                    'status' => 'failed',
                ]);
                $order->update(['status' => 'failed']);
            }

            // Clear the user's cart
            Cart::where('user_id', auth()->id())->delete();

            // Commit the transaction
            DB::commit();

            return $this->responseJsonSuccess($order->load('items.product'), 'Order placed successfully.');

        } catch (\Exception $e) {
            // Rollback transaction in case of error
            DB::rollBack();

            return $this->responseJsonFailed($e->getMessage());
        }
    }

    // Get Order Details
    public function getOrderDetails($id)
    {
        $order = Order::with('transactions')->find($id);

        if (!$order || $order->user_id !== Auth::id()) {
            return $this->responseJsonFailed('Order not found.');
        }

        return $this->responseJsonSuccess($order, 'Order details.');
    }
}

