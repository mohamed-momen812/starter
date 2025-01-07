<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Rennokki\Plans\Models\PlanFeatureModel;
use Rennokki\Plans\Models\PlanModel;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        DB::table('subscribtion_plans')->delete();
        DB::table('plans_features')->delete();
        DB::table('plans_subscriptions')->delete();
        DB::table('plans_usages')->delete();

        $freeTrail = $this->createPlan([
            'id'    => 1,
            'name' => 'Free Trail',
            'code' => 'free_trail',
            'tag' => 'ft',
            'description' => 'The free trail plan for 7 days with beginner features.',
            'price' => 0,
            'currency' => 'SAR',
            'duration' => 14, // in days
        ]);

        $beginnerPlan = $this->createPlan([
            'id' => 2,
            'name' => 'Beginner',
            'code' => 'beginner_plan',
            'tag' => 'sp',
            'description' => 'The base plan.',
            'price' => 19.99,
            'currency' => 'SAR',
            'duration' => 30, // in days
        ]);

        $intermediatePlan = $this->createPlan([
            'id' => 3,
            'name' => 'Itermediate',
            'code' => 'itermediate_plan',
            'tag' => 'gp',
            'description' => 'The medium plan.',
            'price' => 49.99,
            'currency' => 'SAR',
            'duration' => 30, // in days
        ]);

        $enterprisePlan = $this->createPlan([
            'id' => 4,
            'name' => 'Enterprise',
            'code' => 'enterprise_plan',
            'tag' => 'up',
            'description' => 'The biggets plan of all.',
            'price' => 99.99,
            'currency' => 'SAR',
            'duration' => 30, // in days
        ]);

        $beginnerFeatures = [
            'users' => [
                'name' => 'create 5 users',
                'code' => 'beginner_users',
                'description' => 'create only 5 users',
                'type' => 'limit',
                'limit' => 5,
            ],
            'kpis' => [
                'name' => 'create 2 kpis',
                'code' => 'beginner_kpis',
                'description' => 'create only 2 kpis',
                'type' => 'limit',
                'limit' => 2,
            ],
            'dashboards' => [
                'name' => 'create 3 dash',
                'code' => 'beginner_dashboards',
                'description' => 'create only 3 dashboards',
                'type' => 'limit',
                'limit' => 3,
            ],
            'reports' => [
                'name' => 'create 6 reports',
                'code' => 'beginner_reports',
                'description' => 'create only 6 reports',
                'type' => 'limit',
                'limit' => 6,
            ],
        ];

        $features = [];
        foreach($beginnerFeatures as $feature){
            $features[] = $this->createFeatureObj($feature);
        }

        $beginnerPlan = $this->assignFeaturesToPlan($features, $beginnerPlan);

        $intermediateFeatures = [
            'users' => [
                'name' => 'create 10 users',
                'code' => 'itermediate_users',
                'description' => 'create only 10 users',
                'type' => 'limit',
                'limit' => 10,
            ],
            'kpis' => [
                'name' => 'create 5 kpis',
                'code' => 'itermediate_kpis',
                'description' => 'create only 5 kpis',
                'type' => 'limit',
                'limit' => 5,
            ],
            'dashboards' => [
                'name' => 'create 8 dash',
                'code' => 'itermediate_dashboards',
                'description' => 'create only 8 dashboards',
                'type' => 'limit',
                'limit' => 8,
            ],
            'reports' => [
                'name' => 'create 10 reports',
                'code' => 'itermediate_reports',
                'description' => 'create only 10 reports',
                'type' => 'limit',
                'limit' => 10,
            ],
        ];

        $features = [];
        foreach($intermediateFeatures as $feature){
            $features[] = $this->createFeatureObj($feature);
        }

        $intermediatePlan = $this->assignFeaturesToPlan($features, $intermediatePlan);

        $enterpriseFeatures = [
            'users' => [
                'name' => 'create Unlimied users',
                'code' => 'unlimied_users',
                'description' => 'create enterprise users',
                'type' => 'feature',
                // 'limit' => 10,
            ],
            'kpis' => [
                'name' => 'create enterprise kpis',
                'code' => 'unlimied_kpis',
                'description' => 'create Unlimied kpis',
                'type' => 'feature',
                // 'limit' => 5,
            ],
            'dashboards' => [
                'name' => 'create Unlimied dashboards',
                'code' => 'unlimied_dashboards',
                'description' => 'create Unlimied dashboards',
                'type' => 'feature',
                // 'limit' => 8,
            ],
            'reports' => [
                'name' => 'create Unlimied reports',
                'code' => 'Unlimied_reports',
                'description' => 'create Unlimied reports',
                'type' => 'feature',
                // 'limit' => 10,
            ],
        ];

        $features = [];
        foreach($enterpriseFeatures as $feature){
            $features[] = $this->createFeatureObj($feature);
        }

        $enterprisePlan = $this->assignFeaturesToPlan($features, $enterprisePlan);

        //assign beginner features to free trail plan
        $features = [];
        foreach($beginnerFeatures as $feature){
            $features[] = $this->createFeatureObj($feature);
        }

        $freeTrail = $this->assignFeaturesToPlan($features, $freeTrail);
    }

    private function createPlan(array $data)
    {
        $plan = PlanModel::create($data);
        return $plan;
    }

    private function createFeatureObj(array $data)
    {
        $feature = new PlanFeatureModel($data);
        return $feature;
    }

    //assign many features to one plan
    private function assignFeaturesToPlan(array $features, PlanModel $plan)
    {
        $plan->features()->saveMany($features);
        return $plan;
    }

    private function subscribeToPlan(User $user, PlanModel $plan)
    {
        $subscription = $user->subscribeTo($plan);
        return $subscription;
    }
}
