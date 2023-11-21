<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiLogController as ApiLog;

class UserInfoController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission('UserInfoController', 'pread');
        // return $checkPermission;
        if ($checkPermission) {
            $user = auth()->user()->load(['roles', 'roles.permission', 'roles.permission.module']);
            return (new UserResource($user));
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
        // $user = auth()->user();
        // return $user;
        // $roles = Rule::with('permission')->find(auth()->user()->role_id);
        // $user = User::with(['roles','roles.permission','roles.permission.module'])->find(auth()->user()->id);
    }
}
