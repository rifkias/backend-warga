<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\RoleRequest;
use Spatie\Permission\Models\Role;
use App\Http\Resources\Admin\RoleResponse;

class RoleController extends Controller
{
    public function index()
    {
        $data = Role::orderBy('id','DESC')->paginate(10);
        return (RoleResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    public function store(RoleRequest $request)
    {
        $validate = $request->validated();

        $data = Role::create($validate);
        return (New RoleResponse($data))
                ->response()
                ->setStatusCode(201);
    }
    public function show($id)
    {
        $data = Role::findOrFail($id);
        return (New RoleResponse($data))
                ->response()
                ->setStatusCode(201);
    }
    public function update(RoleRequest $request, $id)
    {
        $validate = $request->validated();
        $data = Role::findOrFail($id);
        if($data->update($validate)){
            return (New RoleResponse($data))
                ->response()
                ->setStatusCode(200);

        }

    }
    public function destroy($id)
    {
        $data = Role::findOrFail($id);
        if($data->delete()){
            return (New RoleResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
