<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\UserLoginRequest;
use App\Models\Config\Rule;
class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UserLoginRequest $request)
    {
        $credentials = $request->validated();
        if ($this->CheckCredential($credentials)) {
            $user = auth()->user()->load(['roles','roles.permission','roles.permission.module']);
            // $roles = Rule::with('permission')->find(auth()->user()->role_id);
            // $user = User::with(['roles','roles.permission','roles.permission.module'])->find(auth()->user()->id);
            return (new UserResource($user))->additional([
                'token' => $user->createToken('myAppToken')->plainTextToken,
                'expires_in' => config('sanctum.expiration'),
            ]);
        }
        return response()->json([
            'message' => 'Your credential does not match.',
        ], 422);
    }

    public function CheckCredential($credentials)
    {
        return auth()->attempt($credentials);
    }
}
