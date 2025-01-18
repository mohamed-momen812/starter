<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiTrait;

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function pay(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        try {
            $response = $this->paymentService->initiatePayment(
                $request->input('amount'),
                url('success'),
                url('error')
            );

            return $response->redirect();
        } catch (\Exception $e) {
            return $this->responseJsonFailed('Payment failed: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        try {
            $paymentData = $this->paymentService->completePayment(
                $request->input('paymentId'),
                $request->input('PayerID')
            );

            return $this->responseJsonSuccess(  $paymentData, 'Payment successful');
        } catch (\Exception $e) {
            return $this->responseJsonFailed('Payment failed: ' . $e->getMessage());
        }
    }

    public function error()
    {
        return $this->responseJsonFailed('User cancelled the payment.');
    }
}
