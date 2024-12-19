<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {

        // delete all roles and permissions to reset the database
        DB::table('roles')->delete();
        DB::table('permissions')->delete();
        DB::table('role_has_permissions')->delete();
        DB::table('model_has_roles')->delete();
        DB::table('model_has_permissions')->delete();

        // create permissions for all modules
        $moduleCollection = collect([
            'user',
            'product',
            'order',
            'cart',
            'category',
            'review',
            'permissions',
            'roles',
        ]);

        // loop through modules and create permissions
        $moduleCollection->each(function ($module) {

            // create module and permissions for it
            Module::create(['name' => $module]);

            Permission::firstOrCreate([
                'name' => "view_$module",
            ]);
            Permission::firstOrCreate([
                'name' => "create_$module",
            ]);
            Permission::firstOrCreate([
                'name' => "update_$module",
            ]);
            Permission::firstOrCreate([
                'name' => "delete_$module",
            ]);


            // attach permissions to the module for business reasons
            $ids = [];

            $ids[] = Permission::where('name', "view_$module")->first()->id;
            $ids[] = Permission::where('name', "create_$module")->first()->id;
            $ids[] = Permission::where('name', "update_$module")->first()->id;
            $ids[] = Permission::where('name', "delete_$module")->first()->id;

            $mod = Module::where('name', $module)->first();
            $mod->permissions()->attach($ids);
        });

        // create roles and assign created permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);

        $allPermissions = Permission::all();

        // give all permissions to Admins
        $adminRole->syncPermissions($allPermissions);

        $admins = User::where('type', 'admin')->get();
        if(!empty($admins)){
            foreach($admins as $admin){
                $admin->assignRole($adminRole->name);
            }
        }

        // give all views permissions to Users
        $userPermissions = [];
        $moduleCollection->each(function ($module) use (&$userPermissions) {
            $userPermissions[] = Permission::where('name', "view_$module")->first();
        });

        $userRole->syncPermissions($userPermissions);

        $users = User::where('type', 'user')->get();
        if(!empty($users)){
            foreach($users as $user){
                $user->assignRole($userRole->name);
            }
        }

    }
    // just give role to user and give permission to role it is the best practice
}
