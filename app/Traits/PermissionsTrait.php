<?php

namespace App\Traits;

use App\Models\Module;

trait PermissionsTrait
{

    protected function mapPermissions($permissions)
    {
        $modules = Module::all();

        $mappedPermissions = [];

        foreach ($modules as $module) {
            $modulePermissions = $module->permissions()->pluck('permissions.id')->toArray();
            $i = 0;
            foreach($permissions as $permission){
                if(in_array($permission->id, $modulePermissions)){
                    $mappedPermissions[$module->name][$i] = $permission->name;
                }
                $i++;
            }
            continue;
        }

        return $mappedPermissions;
    }

}
