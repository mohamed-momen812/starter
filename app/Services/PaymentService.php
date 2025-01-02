<?php

namespace App\Services;

use Omnipay\Omnipay;
use App\Models\Payment;
use App\Traits\ApiTrait;
use Exception;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    use ApiTrait;
    private $gateway;

    public function __construct()
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_CLIENT_SECRET'));
        $this->gateway->setTestMode(true); // Set it to 'false' in production
    }

    public function ainitiatePayment($amount, $returnUrl, $cancelUrl)
    {
        try {
            $response = $this->gateway->purchase([
                'amount' => $amount,
                'currency' => env('PAYPAL_CURRENCY', 'USD'),
                'returnUrl' => $returnUrl,
                'cancelUrl' => $cancelUrl,
            ])->send();

            if ($response->isRedirect()) {
                return $response;
            }

            Log::error('Payment failed: ' . $response->getMessage());
            return $this->responseJsonFailed('Payment failed: ' . $response->getMessage());
        } catch (Exception $e) {
            Log::error('Payment exception: ' . $e->getMessage());
            return $this->responseJsonFailed('Payment failed: ' . $e->getMessage());
        }
    }

    public function completePayment($paymentId, $payerId)
    {
        try {
            $transaction = $this->gateway->completePurchase([
                'payer_id'             => $payerId,
                'transactionReference' => $paymentId,
            ]);
            $response = $transaction->send();

            if ($response->isSuccessful()) {
                $data = $response->getData();
                $this->storePayment($data);
                return $data;
            }

            Log::error('Payment failed: ' . $response->getMessage());
            return $this->responseJsonFailed('Payment failed: ' . $response->getMessage());
        } catch (Exception $e) {
            Log::error('Payment exception: ' . $e->getMessage());
            return $this->responseJsonFailed('Payment failed: ' . $e->getMessage());
        }
    }

    private function storePayment($data)
    {
        $payment = new Payment();
        $payment->payment_id = $data['id'];
        $payment->payer_id = $data['payer']['payer_info']['payer_id'];
        $payment->payer_email = $data['payer']['payer_info']['email'];
        $payment->amount = $data['transactions'][0]['amount']['total'];
        $payment->currency = env('PAYPAL_CURRENCY');
        $payment->payment_status = $data['state'];
        $payment->save();
    }
}
