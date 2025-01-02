<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Traits\ApiTrait;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    use ApiTrait;

    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepoInterface)
    {
        $this->userRepo = $userRepoInterface ;
    }

    public function index()
    {
        // $users = $this->userRepo->all();
        $per_page = request()->per_page ?? 5;
        $users = User::Paginate($per_page);

        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));

            $users = $users->filter(function ($user) use ($name) {
                $nameMatch = strpos(strtolower($user->first_name), $name) !== false;
                return $nameMatch;
            });
        }

        if (!empty($users)) return $this->dataPaginate( UserResource::collection($users));
        return $this->responseJsonFailed("No users here", 404);
    }

    public function store(UserRequest $request)
    {
        $data = $this->prepareUserData($request);

        $user = DB::transaction(function () use ($data, $request) {
            $user = $this->userRepo->create($data);

            $this->handleImageUpload($request, $user);
            $this->assignPermissions($request, $user);

            event(new Registered($user));

            return $user;
        });

        return ($user != null ) ?  $this->responseJsonSuccess(new UserResource($user), 'User Created successfuly') : $this->responseJsonFailed('Failed to create user.', 404); ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->userRepo->find($id);
        return $user ? $this->responseJsonSuccess(new UserResource($user)) : $this->responseJsonFailed( "user not found", 404); ;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return $this->responseJsonFailed('User not found.', 404);
        }

        $data = $this->prepareUserData($request);

        $user = DB::transaction(function () use ($data, $request, $id) {
            $user = $this->userRepo->update( $data, $id);

            $this->handleImageUpload($request, $user);
            $this->assignPermissions($request, $user);

            return $user;
        });

        return $user
            ? $this->responseJsonSuccess(new UserResource($user), 'User updated successfully.')
            : $this->responseJsonFailed('Failed to update user.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = $this->userRepo->find($id);
        if (!$user) {
            return $this->responseJsonFailed('User not found.', 404);
        }

        DB::transaction(function () use ($user, $id) {
            $this->removeOldImage($user);
            $this->userRepo->destroy($id);
        });

        return $this->responseJsonSuccess([], 'User deleted successfully');
    }

    // custom methods
    private function prepareUserData($request)
    {
        $data = $request->except(['permission_ids', '_method', 'image', 'type']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }else{
           $data['password'] = Hash::make('password');
        }

        return $data;
    }

    private function handleImageUpload($request, $user)
    {
        if ($request->hasFile('image')) {
            $this->removeOldImage($user);

            $path = $request->file('image')->store('images', 'public');
            $user->images()->create(['path' => $path]);
        }
    }

    private function removeOldImage($user)
    {
        $oldImage = $user->images()->first();
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage->path); // remove old image from storage
            $oldImage->delete(); // remove old image from database
        }
    }

    private function assignPermissions($request, $user)
    {
        if ($request->filled('permission_ids')) {
            $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray();
            $user->syncPermissions($permissions); // Automatically handles permission updates
        }
    }

}
