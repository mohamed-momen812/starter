<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    public $userRepo;

    public function __construct(UserRepository $userRepository){
        $this->userRepo = $userRepository;
    }

    public function register(RegisterRequest $request) {

        $userData = array_merge( $request->validated(), ['password' => bcrypt($request->password) ] );

        $user = $this->userRepo->create($userData);

        // $user->assignRole( "Owner" );

        return $this->sendResponse(['user' => new UserResource($user)], 'User register success', 201);
    }

    public function login(LoginRequest $request){

        if (!auth()->attempt($request->validated())) {
            return $this->sendResponse( response: 'Credintials fail' , code: 401 );
        }

        $token = auth()->user()->createToken('MyApp')->plainTextToken;

        return $this->sendResponse([
            'access_token' => $token,
            'user' => new UserResource(auth()->user()),
        ], "logged in successfully");
    }

    public function logout() {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return $this->sendResponse([], 'User successfully signed out');
    }

     public function refresh() {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        $token = $user->createToken('MyApp')->plainTextToken;

        return $this->sendResponse([
            'access_token' => $token,
            'user' => new UserResource(auth()->user()),
        ], "token refresh successfully");
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return $this->sendResponse([], 'Old password does not match.', 401);
        }

        $user->password = Hash::make($request->new_password);

        $user->save();

        return $this->sendResponse([new UserResource($user)], 'Password changed successfully', 200);
    }



}
