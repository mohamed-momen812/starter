<?php

namespace Database\Seeders;

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

        // create permissions
        $moduleCollection = [ 
            'user',  
        ];

        // loop through modules and create permissions
        foreach ($moduleCollection as $module) {
            Permission::create(['name' => ' view_' . $module ]);
            Permission::create(['name' => 'create_' . $module  ]);
            Permission::create(['name' => 'edit_' . $module  ]);
            Permission::create(['name' => 'delete_' . $module  ]);
        }


        // create roles and assign created permissions
        $adminRole = Role::create(['name' => 'Admin']);
        $userRole = Role::create(['name' => 'User']);
        $ownerRole = Role::create(['name' => 'Owner']);
        $managerRole = Role::create(['name' => 'Manager']);
        $directorRole = Role::create(['name' => 'Director']);

        
        $allPermissions = Permission::all();

        // give all permissions to owner
        $adminRole->syncPermissions($allPermissions);

        $user = User::where('type', 'admin')->first();
        if($user)
            $user->assignRole($adminRole->name);

        

        // give some permissions to user and assign role to each user
        $userRole->syncPermissions($allPermissions);
      
        $users = User::where('type', 'user')->get();
        if(!empty($users)){
            foreach($users as $user){
                $user->assignRole($userRole->name);
            }
        }  

    }
}