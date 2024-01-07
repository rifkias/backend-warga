<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\UserGroupWilayah;
use App\Http\Resources\Config\UserGroupWilayahResource;
use App\Http\Requests\Config\UserGroupWilayahRequest;

use App\Http\Controllers\ApiLogController as ApiLog;

class UserGroupWilayahController extends Controller
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
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pread');

        if($checkPermission){
            $data = UserGroupWilayah::with(['userGroup','wilayah']);
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }

            if ($request->sort_field && $request->sort_type) {
                $sortType = $request->sort_type;
                $sortField = $request->sort_field;

                if($sortField == "user_group_code" || $sortField == "user_group_desc"){
                    $sortField = "user_group_id";
                }

                if($sortField == "wilayah_name"){
                    $sortField = "wilayah_id";
                }

                $data = $data->orderBy($sortField, $sortType);
            }
            if ($request->filled("user_group_id")) {
                $data = $data->where("user_group_id", $request->user_group_id);
            }
            if ($request->filled("wilayah_id")) {
                $data = $data->where("wilayah_id", $request->wilayah_id);
            }
            if($request->filled("user_group_code")){
                $data = $data->whereHas('userGroup',function($query) use($request){
                    $query->where("group_code",$request->user_group_code);
                });
            }
            if($request->filled("user_group_desc")){
                $data = $data->whereHas('userGroup',function($query) use($request){
                    $query->where("group_desc","LIKE","%".$request->user_group_desc."%");
                });
            }
            if($request->filled("wilayah_name")){
                $data = $data->whereHas('wilayah',function($query) use($request){
                    $query->where("kecamatan","LIKE","%".$request->wilayah_name."%")
                    ->orWhere("rt","LIKE","%".$request->wilayah_name."%")
                    ->orWhere("rw","LIKE","%".$request->wilayah_name."%");
                });
            }

            $data = $data->paginate($per_page);
            return (UserGroupWilayahResource::collection($data))
                ->response()
                ->setStatusCode(200);
        }else{
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
    public function store(UserGroupWilayahRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserGroupWilayah::create($validate);
            return (new UserGroupWilayahResource($data))
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
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pread');
        if ($checkPermission) {
            $data = UserGroupWilayah::with(['userGroup','wilayah'])->findOrFail($id);
            return (new UserGroupWilayahResource($data))
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
    public function update(UserGroupWilayahRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserGroupWilayah::with(['wilayah', 'userGroup'])->findOrFail($id);
            if ($data->update($validate)) {
                return (new UserGroupWilayahResource($data))
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
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pdelete');
        if ($checkPermission) {
            $data = UserGroupWilayah::findOrFail($id);
            if ($data->delete()) {
                return (new UserGroupWilayahResource($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }
}
