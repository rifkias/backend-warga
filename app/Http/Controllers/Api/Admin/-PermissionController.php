<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Admin\PermissionRequest;
use App\Http\Resources\Admin\PermissionResponse;
class PermissionController extends Controller
{
    public function index()
    {
        $data = Permission::orderBy('id','DESC')->paginate(10);
        return (PermissionResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    public function store(PermissionRequest $request)
    {
        $validate = $request->validated();

        $data = Permission::create($validate);
        return (New PermissionResponse($data))
                ->response()
                ->setStatusCode(201);
    }
    public function show($id)
    {
        $data = Permission::findOrFail($id);
        return (New PermissionResponse($data))
                ->response()
                ->setStatusCode(201);
    }
    public function update(PermissionRequest $request, $id)
    {
        $validate = $request->validated();
        $data = Permission::findOrFail($id);
        if($data->update($validate)){
            return (New PermissionResponse($data))
                ->response()
                ->setStatusCode(200);
        }

    }
    public function destroy($id)
    {
        $data = Permission::findOrFail($id);
        if($data->delete()){
            return (New PermissionResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
    public function revokePermissionToRole(PermissionRequest $request,$id)
    {
        $validate = $request->validate([
            'name'=>'required'
        ]);
        $data = Permission::find($id);
        $roles = $this->checkRole($request->name);
        if($roles){
            foreach($roles as $role){
                $data->removeRole($role);
            }
            return (New PermissionResponse($data))
            ->response()
            ->setStatusCode(201);
        }else{
            return response()->json([
                'message'=>"Role Not Found"
            ]);
        }
    }
    public function assignPermissionToRole(PermissionRequest $request,$id)
    {
        $validate = $request->validate([
            'name'=>'required'
        ]);
        $data = Permission::find($id);
        $roles = $this->checkRole($request->name);
        if($roles){
            $data->assignRole($roles);
            return (New PermissionResponse($data))
            ->response()
            ->setStatusCode(201);
        }else{
            return response()->json([
                'message'=>"Role Not Found"
            ]);
        }
    }
    public function checkRole($roles)
    {
        $arr = explode(',',$roles);
        foreach($arr as $list){
            $role = Role::where('name',$list)->first();
            if($role){
                continue;
            }else{
                return null;
                break;
            }
        }
        return $arr;
    }
}
