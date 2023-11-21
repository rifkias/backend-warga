<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\UserWilayah;
use App\Http\Resources\Config\UserWilayahResponse;
use App\Http\Requests\Config\UserWilayahRequest;
use App\Http\Controllers\ApiLogController as ApiLog;

class UserWilayahController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserWilayahController', 'pread');
        if ($checkPermission) {
            $data = UserWilayah::with(['user', 'wilayah']);
            $per_page = 10;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type) {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            if ($request->filled("user_id")) {
                $data = $data->where("user_id", $request->user_id);
            }
            if ($request->filled("wilayah_id")) {
                $data = $data->where("wilayah_id", $request->wilayah_id);
            }
            $data = $data->paginate($per_page);
            return (UserWilayahResponse::collection($data))
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
    public function store(UserWilayahRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserWilayahController', 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserWilayah::create($validate);
            return (new UserWilayahResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('UserWilayahController', 'pread');
        if ($checkPermission) {
            $data = UserWilayah::with(['wilayah', 'user'])->findOrFail($id);
            return (new UserWilayahResponse($data))
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
    public function update(UserWilayahRequest $request, $id)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserWilayahController', 'pupdate');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = UserWilayah::with(['wilayah', 'user'])->findOrFail($id);
            if ($data->update($validate)) {
                return (new UserWilayahResponse($data))
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
        $checkPermission    = $this->apiLog->checkPermission('UserWilayahController', 'pdelete');
        if ($checkPermission) {
            $data = UserWilayah::with(['wilayah', 'user'])->findOrFail($id);
            if ($data->delete()) {
                return (new UserWilayahResponse($data))
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
