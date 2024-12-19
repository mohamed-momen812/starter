<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Hash;
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
        $users = $this->userRepo->all();

        if (!empty($users)) return $this->responseJsonSuccess(UserResource::collection($users));

        return $this->responseJsonFailed("No users here", 404);
    }

    public function store(UserRequest $request)
    {
        $data = $request->except('password');

        $data['password'] = Hash::make($request->password);

        $user = $this->userRepo->create($data);

        // TODO  make gate to assign admin role
        // TODO  handle image upload
        $user->assignRole( $request->type );

        if ($request->permission_ids) {
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission->name);
            }
        }

        return ($user != null ) ?  $this->responseJsonSuccess(new UserResource($user), 'User Created successfuly') : $this->responseJsonFailed() ;
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
        return $user ? $this->responseJsonSuccess(new UserResource($user)) : $this->responseJsonFailed( "user not found") ;
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

        $data = $request->except(['permission_ids', '_method']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // TODO  handle image upload
        $user = $this->userRepo->update($data, $id);

        if ($request->filled('type')) {
            $user->removeRole($user->type);
            $user->assignRole($request->type);
        }

        if ($request->filled('permission_ids')) {
            $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name');
            foreach ($permissions as $permission) {
                $user->givePermissionTo($permission);
            }
        }

        return $user ? $this->responseJsonSuccess(new UserResource($user)) : $this->responseJsonFailed();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->userRepo->destroy($id);

        // TODO  handle image upload
        return $this->responseJsonSuccess([], 'User deleted successfully');
    }

}
