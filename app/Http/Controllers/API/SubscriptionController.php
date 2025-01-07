<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlanResource;
use App\Models\Plan;
use App\Models\User;
use App\Services\PaymentService;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
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
        $plans = Plan::all();

        return $this->dataPaginate(PlanResource::collection($plans));
    }

    public function subscribe(Request $request)
    {

        $userId = $request->user()->id;

        $planId = $request->plan_id;

        $plan = Plan::find($planId);

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

            $user = User::find($userId);
            $subscription = $user->subscriptions()->create([
                'plan_id' => $plan->id,
                'starts_at' => now(),
                'ends_at' => now()->addDays($plan->duration),
            ]);

            foreach ($plan->features as $feature) {
                $subscription->featureUsages()->create([
                    'feature_id' => $feature->id,
                    'used' => 0,
                ]);
            }

            DB::commit();

            return $this->responseJsonSuccess([
                    'user_id' => $userId,
                    'plan_id' => $planId,
                    'start_date' => now(),
                    'end_date' =>  now()->addDays($plan->duration),
            ], 'Subscription successful');

        }catch (\Exception $e) {
            DB::rollBack();
            return $this->responseJsonFailed($e->getMessage(), 400);
        }
    }

    public function canUseFeature(User $user, $featureCode)
    {
        $subscription = $user->activeSubscription();
        if (!$subscription) return $this->responseJsonFailed('No active subscription found', 400);

        $feature = $subscription->plan->features->where('code', $featureCode)->first();
        $usage = $subscription->featureUsages->where('feature_id', $feature->id)->first();

        return $usage && $usage->used < $feature->value;
    }

}
