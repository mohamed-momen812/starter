<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiTrait;

    private $roleRepo;

    public function __construct(RoleRepositoryInterface $roleRepoInterface)
    {
        $this->roleRepo = $roleRepoInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return $this->responseJsonSuccess( RoleResource::collection($roles) );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        if (! Gate::allows('create-role')) {
            return $this->responseJsonFailed('You do not have permission to create role', 403,);
        }

        $role = $this->roleRepo->create($request->validated());

        if($request->permission_ids != null){
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            $role->syncPermissions($permissions);
        }

        return $this->responseJsonSuccess( new RoleResource($role), 'Role successfuly created', 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        return $this->responseJsonSuccess( new RoleResource($role) );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, $id)
    {
        if (! Gate::allows('update-role')) {
            return $this->responseJsonFailed('You do not have permission to update role', 403,);
        }

        $role = $this->roleRepo->update($request->except('permission_ids','_method') , $id);

        if($request->permission_ids != null){
            $permissions = Permission::whereIn('id', $request->permission_ids)->get();
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission);
            } // can't use syncPermissions here because it will remove all permissions first
        }

        return $this->responseJsonSuccess( new RoleResource($role), 'Role successfuly updated' );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('delete-role')) {
            return $this->responseJsonFailed('You do not have permission to delete role', 403,);
        }

        $role = $this->roleRepo->destroy($id);

        return $this->responseJson();
    } // TODO this method does not work

}
