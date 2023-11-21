<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Config\UserGroupResponse;
use App\Http\Requests\Config\UserGroupRequest;
use App\Models\Config\UserGroup;
use Illuminate\Support\Facades\Log;

use App\Http\Controllers\ApiLogController as ApiLog;

class UserGroupController extends Controller
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
            // $data = UserGroup::with(['parent']);
            $data = UserGroup::leftJoin('user_group as parent', 'user_group.parent_id','=','parent.id')
            ->select("user_group.*",'parent.group_code as parent_code','parent.group_desc as parent_desc', 'parent.parent_id as parent_parent_id');
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type) {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            if ($request->filled("group_code")) {
                $data = $data->where("user_group.group_code",'LIKE', '%'.$request->group_code.'%');
            }
            if ($request->filled("group_desc")) {
                $data = $data->where("user_group.group_desc",'LIKE', '%'.$request->group_desc . '%');
            }
            if ($request->filled("parent_desc")) {
                $data = $data->whereHas('parent', function ($q) use ($request) {
                    return $q->where(
                        "group_desc",
                        "LIKE",
                        "%" . $request->parent_desc . "%"
                    );
                });
            }
            $data = $data->paginate($per_page);
            // return $data;
            return (UserGroupResponse::collection($data))
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
    public function store(UserGroupRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserGroup::create($validate);
            return (new UserGroupResponse($data))
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
            $data = UserGroup::with(['parent'])->findOrFail($id);
            $tree = $this->treeStructure($data->descendants_and_self());
            return $tree;
            return (new UserGroupResponse($data))
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
    public function update(UserGroupRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserGroup::findOrFail($id);
            if ($data->update($validate)) {
                return (new UserGroupResponse($data))
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
            $data = UserGroup::findOrFail($id);
            if ($data->delete()) {
                return (new UserGroupResponse($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }
    function treeStructure($data) {
        return $data;
    }
}
