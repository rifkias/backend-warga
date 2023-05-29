<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\Warga;
use App\Http\Resources\MasterData\WargaResponse;
use App\Http\Requests\MasterData\WargaRequest;

class WargaController extends Controller
{
    public function index(Request $request)
    {
        $data = Warga::with(["house","house.wilayah"]);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('nik')){
            $data =  $data->where("nik","LIKE","%".$request->nik."%");
        }
        if($request->has('kk_number')){
            $data =  $data->where("kk_number","LIKE","%".$request->kk_number."%");
        }
        if($request->has('name')){
            $data =  $data->where("name","LIKE","%".$request->name."%");
        }
        if($request->has('gender')){
            $data =  $data->where("gender","LIKE","%".$request->gender."%");
        }
        if($request->has('birth_place')){
            $data =  $data->where("birth_place","LIKE","%".$request->birth_place."%");
        }
        if($request->has('religion')){
            $data =  $data->where("religion","LIKE","%".$request->religion."%");
        }
        if($request->has("trash")){
            if($request->trash){
                $data = $data->onlyTrashed();
            }
        }
        $data = $data->paginate($per_page);
        return (WargaResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WargaRequest $request)
    {
        $validate = $request->validated();
        $data = Warga::with(["house","house.wilayah"])->create($validate);
        return (New WargaResponse($data))
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
        $data = Warga::with(["house","house.wilayah"])->findOrFail($id);
        return (New WargaResponse($data))
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
    public function update(WargaRequest $request, $id)
    {
        $validate = $request->validated();

        $data = Warga::with(["house","house.wilayah"])->findOrFail($id);
        if($data->update($validate)){
            return (New WargaResponse($data))
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
        $data = Warga::with(["house","house.wilayah"])->findOrFail($id);
        $data->deleted_by = auth()->user()->id;
        $data->save();
        if($data->delete()){
            return (New WargaResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
