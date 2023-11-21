<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ApiLogController as ApiLog;

use Auth,Log;

class UserController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserController', 'pread');
        if ($checkPermission) {
            $data = User::with(['roles']);
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->filled("name")) {
                $data = $data->where("name", "LIKE", "%" . $request->name . "%");
            }
            if ($request->filled("email")) {
                $data = $data->where("email", "LIKE", "%" . $request->email . "%");
            }
            if ($request->filled("role_desc")) {
                $data = $data->whereHas('roles', function ($q) use ($request) {
                    $q->where("role_desc", "LIKE", "%" . $request->role_desc . "%");
                });
            }
            if ($request->sort_field && $request->sort_type) {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            $data = $data->paginate($per_page);

            return (UserResource::collection($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserController', 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role_id' => $request->role_id,
                'password' => bcrypt($request->password)
            ]);
            return (new UserResource($data))
                ->response()
                ->setStatusCode(201);
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserController', 'pread');
        if ($checkPermission) {
            $data = User::with(['roles', 'roles.permission'])->findOrFail($id);
            return (new UserResource($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserController', 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();
            $data = User::findOrFail($id);

            // Hash Password Before Update
            if($validate['password']){
                $validate['password'] = bcrypt($validate['password']);
            }

            if ($data->update($validate)) {
                return (new UserResource($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
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
        $checkPermission    = $this->apiLog->checkPermission('UserController', 'pdelete');
        if ($checkPermission) {
            $data = User::findOrFail($id);
            if ($data->delete()) {
                return (new UserResource($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }
    public function assignPermission(Request $request, $id)
    {
        // $user = Auth::user();
        // return $user;

        $validate = $request->validate([
            'name' => 'required'
        ]);
        $data = User::find($id);
        $permission = Permission::findByName($request->name);
        $data->givePermissionTo($permission);
        // return ['guard_name'=>'sanctum','name'=>$request->name];
        // if(){
        //     return (New UserResource($data))
        //     ->response()
        //     ->setStatusCode(200);
        // }else{
        //     return response()->json([
        //         'message'=>"Permission Not Found"
        //     ]);
        // }
    }
}
