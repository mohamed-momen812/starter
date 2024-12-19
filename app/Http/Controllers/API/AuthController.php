<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    use ApiTrait;

    public $userRepo;

    public function __construct(UserRepository $userRepository){
        $this->userRepo = $userRepository;
    }

    public function register(RegisterRequest $request) {

        $userData = array_merge( $request->validated(), ['password' => bcrypt($request->password) ] );

        // TODO: handle image upload

        $user = $this->userRepo->create($userData);

        $user->assignRole( "User" );
        // TODO: make gate to assign role admin

        return $this->responseJsonSuccess(['user' => new UserResource($user)], 'User successfully registered', 201);
    }

    public function login(LoginRequest $request){

        if (!auth('web')->attempt($request->validated())) {
            return $this->responseJsonFailed( 'Credintials failed' ,  401 );
        } // use guard web cause method attempt doesn't work with guard api

        $token = auth()->user()->createToken('MyApp')->plainTextToken;

        return $this->responseJsonSuccess([
            'access_token' => $token,
            'user' => new UserResource(auth()->user()),
        ], " User logged in successfully");
    }

    public function logout() {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        return $this->responseJsonSuccess([], 'User successfully signed out');
    }

     public function refresh() {
        $user = auth()->user();
        $user->currentAccessToken()->delete();

        $token = $user->createToken('MyApp')->plainTextToken;

        return $this->responseJsonSuccess([
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
            return $this->responseJsonFailed( 'Old password does not match.', 401);
        }

        $user->password = Hash::make($request->new_password);

        $user->save();

        return $this->responseJsonSuccess(['user' => new UserResource($user)], 'Password changed successfully', 200);
    }
}
