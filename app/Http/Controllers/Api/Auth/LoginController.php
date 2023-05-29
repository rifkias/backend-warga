<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\UserLoginRequest;

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
            $user = auth()->user();
            return (new UserResource($user))->additional([
                'token' => $user->createToken('myAppToken')->plainTextToken,
            ]);
        }
        return response()->json([
            'message' => 'Your credential does not match.',
        ], 401);
    }

    public function CheckCredential($credentials)
    {
        return auth()->attempt($credentials);
    }
}
