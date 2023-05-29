<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\Module;
use App\Http\Resources\Config\ModuleResponse;
use App\Http\Requests\Config\ModuleRequest;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $data = Module::query();
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('module_name')){
            $data =  $data->where("module_name","LIKE","%".$request->module_name."%");
        }
        if($request->has('module_desc')){
            $data =  $data->where("module_desc","LIKE","%".$request->module_desc."%");
        }
        $data = $data->paginate($per_page);
        return (ModuleResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        $validate = $request->validated();

        $data = Module::create([
            'module_name'=>$request->module_name,
            'module_desc' => $request->module_desc,
        ]);
        return (New ModuleResponse($data))
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
        $data = Module::findOrFail($id);
        return (New ModuleResponse($data))
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
    public function update(ModuleRequest $request, $id)
    {
        $validate = $request->validated();

        $data = Module::findOrFail($id);
        if($data->update($validate)){
            return (New ModuleResponse($data))
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
        $data = Module::findOrFail($id);
        if($data->delete()){
            return (New ModuleResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
