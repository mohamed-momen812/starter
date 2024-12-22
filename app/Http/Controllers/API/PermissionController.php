<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\UserPermissionResource;
use App\Http\Resources\UserResource;
use App\Interfaces\PermissionRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use App\Traits\ApiTrait;
use App\Traits\PermissionsTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    use ApiTrait, PermissionsTrait;

    private $permissionRepo;
    private $userRepo;

    public function __construct(PermissionRepositoryInterface $permissionRepoInterface, UserRepositoryInterface $userRepoInterface)
    {
        $this->permissionRepo = $permissionRepoInterface;
        $this->userRepo = $userRepoInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = $this->permissionRepo->all();
        return $this->responseJsonSuccess( PermissionResource::collection($permissions) );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissionRequest $request)
    {
        $permission = $this->permissionRepo->create($request->validated());

        if ($request->role_ids != null){
            $roles = Role::whereIn('id',$request->role_ids)->get();
            $permission->syncRoles($roles);
        }

        return $this->responseJsonSuccess( new PermissionResource($permission) );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return $this->responseJsonSuccess( new PermissionResource($permission) );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermissionRequest $request, $id)
    {
        $permission = $this->permissionRepo->update($request->except('_method','role_ids') , $id);

        if ($request->role_ids != null){
            foreach ($request->role_ids as $role_id) {
                 $roles = Role::whereIn('id',$request->role_ids)->get();
                $permission->assignRole($roles);
            }
        } // can't use syncRoles here because it will remove all roles first

        return $this->responseJsonSuccess( new PermissionResource($permission) );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = $this->permissionRepo->destroy($id);
        return $this->responseJsonSuccess();
    } // TODO this method doesn work

    public function getPermissions($id)
    {
        $user = User::where('id', $id)->get(); // must be get to retrive user as collection not object

        if($user == null) return $this->responseJsonFailed("User not found");

        return $this->responseJsonSuccess( UserPermissionResource::collection($user));
    }

    public function updatePermissions(Request $request, $id)
    {
        $user = $this->userRepo->find($id);

        if($user == null) return $this->responseJsonFailed("User not found");

        if ($request->filled('permission_ids')) {

            $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name');
            $user->syncPermissions($permissions);

            return $this->responseJsonSuccess(new UserResource($user), 'Permissions updated successfully', 200);
        }
    }
}
