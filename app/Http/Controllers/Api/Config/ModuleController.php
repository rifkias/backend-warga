<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\Module;
use App\Http\Resources\Config\ModuleResponse;
use App\Http\Requests\Config\ModuleRequest;
use App\Http\Controllers\ApiLogController as ApiLog;

class ModuleController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('ModuleController', 'pread');
        if ($checkPermission) {
            $data = Module::query();
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type) {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            if ($request->filled('module_name')) {
                $data =  $data->where("module_name", "LIKE", "%" . $request->module_name . "%");
            }
            if ($request->filled('module_desc')) {
                $data =  $data->where("module_desc", "LIKE", "%" . $request->module_desc . "%");
            }
            $data = $data->paginate($per_page);
            return (ModuleResponse::collection($data))
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
    public function store(ModuleRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('ModuleController', 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = Module::create([
                'module_name' => $request->module_name,
                'module_desc' => $request->module_desc,
            ]);
            return (new ModuleResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('ModuleController', 'pread');
        if ($checkPermission) {
            $data = Module::findOrFail($id);
            return (new ModuleResponse($data))
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
    public function update(ModuleRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('ModuleController', 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = Module::findOrFail($id);
            if ($data->update($validate)) {
                return (new ModuleResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('ModuleController', 'pdelete');
        if ($checkPermission) {
            $data = Module::findOrFail($id);
            if ($data->delete()) {
                return (new ModuleResponse($data))
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
