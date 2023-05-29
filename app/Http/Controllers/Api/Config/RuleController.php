<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\Rule;
use App\Http\Resources\Config\RuleResponse;
use App\Http\Requests\Config\RuleRequest;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        $data = Rule::query();
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('role_name')){
            $data =  $data->where("role_name","LIKE","%".$request->role_name."%");
        }
        if($request->has('role_desc')){
            $data =  $data->where("role_desc","LIKE","%".$request->role_desc."%");
        }
        $data = $data->paginate($per_page);
        return (RuleResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuleRequest $request)
    {
        $validate = $request->validated();

        $data = Rule::create([
            'role_name'=>$request->role_name,
            'role_desc' => $request->role_desc,
        ]);
        return (New RuleResponse($data))
                ->response()
                ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Rule::findOrFail($id);
        return (New RuleResponse($data))
                ->response()
                ->setStatusCode(200);
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
        $validate = $request->validated();

        $data = Rule::findOrFail($id);
        if($data->update($validate)){
            return (New RuleResponse($data))
                ->response()
                ->setStatusCode(200);

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
        $data = Rule::findOrFail($id);
        if($data->delete()){
            return (New RuleResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
