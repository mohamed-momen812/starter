<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use App\Services\PaymentService;
use App\Traits\ApiTrait;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionController extends Controller
{
    use ApiTrait;

    private $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function plans()
    {
        $plans = SubscriptionPlan::all();

        return $this->responseJsonSuccess($plans, 'Subscription plans');
    }

    public function subscribe(Request $request)
    {

        $userId = $request->user()->id;

        $planId = $request->plan_id;

        $plan = SubscriptionPlan::find($planId);

        if(!$plan) {
            return $this->responseJsonFailed('No plane found with this id', 404);
        }

        // $paymentResult = $this->paymentService->initiatePayment(
        //     $plan->price,
        //     route('success'),
        //     route('error')
        // );

        DB::beginTransaction();
        try {
            $startDate = Carbon::now();
            $endDate = Carbon::now()->addDays($plan->duration_days);

            UserSubscription::create([
                'user_id' => $userId,
                'subscription_plan_id' => $planId,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => 'active',
            ]);

            DB::commit();

            return $this->responseJsonSuccess([
                    'user_id' => $userId,
                    'subscription_plan_id' => $planId,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'status' => 'active',
            ], 'Subscription successful');

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->responseJsonFailed($e->getMessage(), 400);
        }
    }
}
