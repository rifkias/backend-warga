<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\RolePermission;
use App\Http\Resources\Config\RolePermissionResponse;
use App\Http\Requests\Config\RolePermissionRequest;
use App\Http\Controllers\ApiLogController as ApiLog;

class RolePermissionController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pread');
        if ($checkPermission) {
            // $data = RolePermission::with(['role', 'module']);
            $data = RolePermission::join('rules', 'rules.id', '=', 'role_permissions.role_id')
                ->join('modules', 'modules.id', '=', 'role_permissions.module_id')
                ->select(
                    'role_permissions.*',
                    'rules.role_name as role_name',
                    'rules.role_desc as role_desc',
                    'modules.module_name as module_name',
                    'modules.module_desc as module_desc',

                );
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type) {

                if (
                    $request->sort_field == "role_name" && $request->sort_type != 'none'
                ) {
                    $data = $data->orderBy('rules.role_name', $request->sort_type);
                } elseif ($request->sort_field == "role_desc" && $request->sort_type != 'none') {
                    $data = $data->orderBy('rules.role_desc', $request->sort_type);
                } elseif ($request->sort_field == "module_name" && $request->sort_type != 'none') {
                    $data = $data->orderBy('modules.module_name', $request->sort_type);
                } elseif ($request->sort_field == "module_desc" && $request->sort_type != 'none') {
                    $data = $data->orderBy('modules.module_desc', $request->sort_type);
                } else {
                    $data = $data->orderBy($request->sort_field, $request->sort_type);
                }
            }
            if ($request->filled("role_id")) {
                $data = $data->where("role_id", $request->role_id);
            }
            if ($request->filled("role_name")) {
                $data = $data->whereHas('role', function ($q) use ($request) {
                    return $q->where(
                        "rules.role_name",
                        "LIKE",
                        "%" . $request->role_name . "%"
                    );
                });
            }
            if ($request->filled("role_desc")) {
                $data = $data->whereHas('role', function ($q) use ($request) {
                    return $q->where(
                        "rules.role_desc",
                        "LIKE",
                        "%" . $request->role_desc . "%"
                    );
                });
            }
            if ($request->filled("module_id")) {
                $data =  $data->where("module_id", $request->module_id);
            }
            if ($request->filled("module_name")) {
                $data = $data->whereHas('module', function ($q) use ($request) {
                    return $q->where(
                        "modules.module_name",
                        "LIKE",
                        "%" . $request->module_name . "%"
                    );
                });
            }
            if ($request->filled("module_desc")) {
                $data = $data->whereHas('module', function ($q) use ($request) {
                    return $q->where(
                        "modules.module_desc",
                        "LIKE",
                        "%" . $request->module_desc . "%"
                    );
                });
            }

            $data = $data->paginate($per_page);
            // return $data;
            return (RolePermissionResponse::collection($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
            ], 403);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolePermissionRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();
            $customValidate = RolePermission::where([
                "role_id" => $request->role_id, 'module_id' => $request->module_id,
            ])->first();
            if ($customValidate) {
                return response()->json([
                    'errors' => [
                        "role_id" => ["Duplicate Data"]
                    ]
                ], 422);
            }

            $data = RolePermission::create([
                'role_id'   => $request->role_id,
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
            return (new RolePermissionResponse($data))
                ->response()
                ->setStatusCode(201);
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
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
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pread');
        if ($checkPermission) {
            $data = RolePermission::with(['role', 'module'])->findOrFail($id);
            return (new RolePermissionResponse($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
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
    public function update(RolePermissionRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();
            $data = RolePermission::findOrFail($id);
            $customValidate = RolePermission::where([
                "role_id" => $request->role_id, 'module_id' => $request->module_id,
            ])->where('id', '<>', $id)->first();
            if ($customValidate) {
                return response()->json([
                    'errors' => [
                        "role_id" => ["Duplicate Data"]
                    ]
                ], 422);
            }
            if ($data->update($validate)) {
                return (new RolePermissionResponse($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
            ], 403);
        }
    }
    public function getByRole(Request $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pread');
        if ($checkPermission) {
            $data = RolePermission::with(['role', 'module'])->where('role_id', $id);
            $data = $data->get();



            return (RolePermissionResponse::collection($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
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
        $checkPermission    = $this->apiLog->checkPermission('RolePermissionController', 'pupdate');
        if ($checkPermission) {
            $data = RolePermission::findOrFail($id);
            if ($data->delete()) {
                return (new RolePermissionResponse($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'errors' => 'Forbidden access'
            ], 403);
        }
    }
}
