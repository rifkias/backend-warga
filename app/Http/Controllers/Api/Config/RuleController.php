<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\Rule;
use App\Http\Resources\Config\RuleResponse;
use App\Http\Requests\Config\RuleRequest;
use App\Http\Controllers\ApiLogController as ApiLog;

class RuleController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('RuleController', 'pread');
        if ($checkPermission) {
            $data = Rule::query();
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type && $request->sort_type !== 'none') {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            if ($request->filled('role_name')) {
                $data =  $data->where("role_name", "LIKE", "%" . $request->role_name . "%");
            }
            if ($request->filled('role_desc')) {
                $data =  $data->where("role_desc", "LIKE", "%" . $request->role_desc . "%");
            }
            $data = $data->paginate($per_page);
            return (RuleResponse::collection($data))
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
    public function store(RuleRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('RuleController', 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = Rule::create([
                'role_name' => $request->role_name,
                'role_desc' => $request->role_desc,
            ]);
            return (new RuleResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('RuleController', 'pread');
        if ($checkPermission) {
            $data = Rule::findOrFail($id);
            return (new RuleResponse($data))
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
    public function update(RuleRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('RuleController', 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = Rule::findOrFail($id);
            if ($data->update($validate)) {
                return (new RuleResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('RuleController', 'pdelete');
        if ($checkPermission) {
            $data = Rule::findOrFail($id);
            if ($data->delete()) {
                return (new RuleResponse($data))
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
