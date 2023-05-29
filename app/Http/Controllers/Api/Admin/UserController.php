<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Auth;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::with(['roles','roles.permission'])->orderBy('id','DESC')->paginate(10);
        return (UserResource::collection($data))
                ->response()
                ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $validate = $request->validated();

        $data = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
            'password' => bcrypt($request->password)
        ]);
        return (New UserResource($data))
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
        $data = User::with(['roles','roles.permission'])->findOrFail($id);
        return (New UserResource($data))
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
    public function update(UserRequest $request, $id)
    {
        $validate = $request->validated();

        $data = User::findOrFail($id);
        if($data->update($validate)){
            return (New UserResource($data))
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
        $data = User::findOrFail($id);
        if($data->delete()){
            return (New UserResource($data))
                ->response()
                ->setStatusCode(200);
        }
    }
    public function assignPermission(Request $request,$id)
    {
        // $user = Auth::user();
        // return $user;

        $validate = $request->validate([
            'name'=>'required'
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
