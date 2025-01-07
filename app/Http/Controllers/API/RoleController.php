<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Interfaces\RoleRepositoryInterface;
use App\Traits\ApiTrait;
use Illuminate\Support\Facades\DB;
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
        if($roles->isEmpty()){
            return $this->responseJsonFailed('No roles found', 404);
        }

        if (request()->has('name')) {
            $name = strtolower(request()->input('name'));

            $roles = $roles->filter(function ($role) use ($name) {
                $nameMatch = strpos(strtolower($role->first_name), $name) !== false;
                return $nameMatch;
            });
        }

        return $this->dataPaginate( RoleResource::collection($roles) );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $role = DB::transaction(function () use ($request) {
            $role = $this->roleRepo->create($request->validated());

            if ($request->filled('permission_ids')) {
                $permissions = Permission::whereIn('id', $request->permission_ids)->pluck('name')->toArray();
                $role->syncPermissions($permissions); 
            }
            
            return $role;
        });
        
        return $role
        ? $this->responseJsonSuccess( new RoleResource($role), 'Role successfuly created', 201 )
        : $this->responseJsonFailed('Failed to create role', 500);
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
        $role = $this->roleRepo->find($id);
        if (!$role) {
            return $this->responseJsonFailed('Role not found', 404);
        }

        $role = DB::transaction(function () use ($request, $role, $id) {
            $role = $this->roleRepo->update($request->except('permission_ids','_method') , $id);

            if($request->permission_ids != null){
                $permissions = Permission::whereIn('id', $request->permission_ids)->get();
                foreach ($permissions as $permission) {
                    $role->givePermissionTo($permission);
                } // can't use syncPermissions here because it will remove all permissions first
            }

            return $role;
        });

        return $role
        ? $this->responseJsonSuccess( new RoleResource($role), 'Role successfuly created', 201 )
        : $this->responseJsonFailed('Failed to create role', 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (!$this->roleRepo->find($id)) {
            return $this->responseJsonFailed('Role not found', 404);
        }

        $role = $this->roleRepo->destroy($id);

        return $this->responseJson();
    } // TODO this method does not work

}
