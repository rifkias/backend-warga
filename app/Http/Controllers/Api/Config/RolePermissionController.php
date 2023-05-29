<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\RolePermission;
use App\Http\Resources\Config\RolePermissionResponse;
use App\Http\Requests\Config\RolePermissionRequest;

class RolePermissionController extends Controller
{
    public function index(Request $request)
    {
        $data = RolePermission::with(['role','module']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has("role_id")){
            $data = $data->where("role_id",$request->role_id);
        }
        if($request->has("module_id")){
            $data = $data->where("module_id",$request->module_id);
        }
        $data = $data->paginate($per_page);
        return (RolePermissionResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolePermissionRequest $request)
    {
        $validate = $request->validated();

        $data = RolePermission::create([
            'role_id'   =>$request->role_id,
            'module_id' => $request->module_id,
            'pcreate'   => $request->pcreate,
            'pread'     => $request->pread,
            'pupdate'   => $request->pupdate,
            'pdelete'   => $request->pdelete,
            'pupload'   => $request->pupload,
            'pcustom1'  => $request->pcustom1,
            'pcustom2'  => $request->pcustom2,
            'pcustom3'  => $request->pcustom3,
            'pcustom4'  => $request->pcustom4,
            'pcustom5'  => $request->pcustom5,
        ]);
        return (New RolePermissionResponse($data))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = RolePermission::with(['role','module'])->findOrFail($id);
        return (New RolePermissionResponse($data))
                ->response()
                ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RolePermissionRequest $request, $id)
    {
        $validate = $request->validated();
        return $validate;
        $data = RolePermission::findOrFail($id);
        if($data->update($validate)){
            return (New RolePermissionResponse($data))
                ->response()
                ->setStatusCode(200);

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = RolePermission::findOrFail($id);
        if($data->delete()){
            return (New RolePermissionResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
