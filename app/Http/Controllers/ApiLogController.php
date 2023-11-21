<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Log, Auth, Session, File;
use App\Models\Config\RolePermission;
class ApiLogController extends Controller
{
    public static function checkPermission($controllerName, $rolePermissionMethod)
    {
        $user = auth()->user();
        $check = RolePermission::join('modules', 'modules.id', '=', 'role_permissions.module_id')
            ->where([
                'role_permissions.role_id'=>@$user->role_id,
                'modules.module_name'=>@$controllerName,
            ])
            ->first();
        if($check){
            return @$check[$rolePermissionMethod];
        }
        return false;
        // $check = [
        //     'role_id' => @$user->role_id,
        //     'module.module_name' => $controllerName,
        //     $rolePermissionMethod => 1
        // ];
        // return json_encode($check);
        // // $user = auth()->user()->load(['roles', 'roles.permission', 'roles.permission.module']);
        // $permission_roles = $user->roles->permission;
        // if (count($permission_roles) > 0) {

        //     foreach ($permission_roles as $key => $permision) {

        //         if ($permision->module->module_name == $controllerName) {

        //             return $permision->$rolePermissionMethod;
        //         }
        //     }
        // }

    }
}
