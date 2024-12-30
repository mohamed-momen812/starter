<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use ApiTrait;

    public function redirectToProvider() {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        $user = Socialite::driver('google')->user();

        $findUser = User::where('social_id', $user->id)->first();

        if($findUser){
            $token = $findUser->createToken('API Token')->plainTextToken;
            return $this->responseJsonSuccess([
                'token' => $token,
                'user' => $findUser,
            ], 'User login success');
        }else{
            $newUser = User::create([
                'first_name' => explode(' ', $user->name)[0],
                'last_name' => explode(' ', $user->name)[1],
                'email' => $user->email,
                'social_id' => $user->id,
                'social_type' => 'google',
                'password' => Hash::make('my-google')
            ]);

            $token = $newUser->createToken('API Token')->plainTextToken;
            return $this->responseJsonSuccess([
                'token' => $token,
                'user' => $newUser,
            ], 'User login success', 201);
        }
    }


}
