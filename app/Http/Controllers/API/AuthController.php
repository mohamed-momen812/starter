<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Mail\ResetPasswordMail;
use App\Repositories\UserRepository;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{

    use ApiTrait;

    public $userRepo;

    public function __construct(UserRepository $userRepository){
        $this->userRepo = $userRepository;
    }

    public function register(RegisterRequest $request) {
        $userData = $this->prepareUserData($request);

        $user = DB::transaction(function () use ($userData, $request) {
            $user = $this->userRepo->create($userData);

            $this->handleImageUpload($request, $user);

            // event(new Registered($user)); // event to make listener send verification email

            return $user;
        });

        return $user
        ? $this->responseJsonSuccess(['user' => new UserResource($user)], 'User successfully registered', 201)
        : $this->responseJsonFailed('Failed to register user.');
    }

    public function login(LoginRequest $request){

        if (!auth('web')->attempt($request->validated())) {
            return $this->responseJsonFailed( 'Credintials failed' ,  401 );
        } // use guard web cause method attempt doesn't work with guard api

        $token = auth("web")->user()->createToken('MyApp')->plainTextToken;

        return $this->responseJsonSuccess([
            'access_token' => $token,
            'user' => new UserResource(auth('web')->user()),
        ], " User logged in successfully");
    }

    public function logout() {
        $user = auth()->user();
        $user->currentAccessToken()->delete();
        // $user->tokens()->delete(); // delete all tokens of the user

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

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = $this->userRepo->findByEmail($request->email);

        if (!$user) {
            return $this->responseJsonFailed('User not found', 404);
        }

        $token = Str::random(60);

        $user->update(['remember_token' => $token]);

        Mail::to($user->email)->send(new ResetPasswordMail($token));

        return $this->responseJsonSuccess([], 'Password reset email sent successfully');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = $this->userRepo->findbyToken($request->token);

        if (!$user) {
            return $this->responseJsonFailed('Invalid token', 401);
        }

        $user->update([
            'password' => Hash::make($request->new_password),
            'remember_token' => null,
        ]);

        return $this->responseJsonSuccess([], 'Password reset successfully');
    }

    // custom functions
    Private function handleImageUpload($request, $user) {
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $user->images()->create(['path' => $path]);
        }
    }

    private function prepareUserData($request)
    {
        $data = $request->except(['image', 'password']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        return $data;
    }
}
