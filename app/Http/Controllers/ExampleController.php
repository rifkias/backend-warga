<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function coba()
    {
        $rule = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [ 'required', 'string', 'email', 'max:255','unique:users,email',],
            'password' => ['required','min:8'],
        ];
        $rule["test"]= ["required",'string'];
        return $rule;
    }
}
