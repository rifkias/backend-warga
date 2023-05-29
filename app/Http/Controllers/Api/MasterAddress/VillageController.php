<?php

namespace App\Http\Controllers\Api\MasterAddress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterAddress\Village;
use App\Http\Resources\MasterAddress\VillageResponse;
use App\Http\Requests\MasterAddress\VillageRequest;

class VillageController extends Controller
{
    public function index(Request $request)
    {
        $data = Village::with(['district']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('district_id')){
            $data = $data->where("district_id",$request->district_id);
        }
        if($request->has('village_name')){
            $data =  $data->where("village_name","LIKE","%".$request->village_name."%");
        }
        $data = $data->paginate($per_page);
        return (VillageResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(VillageRequest $request)
    {
        $validate = $request->validated();

        $data = Village::create([
            'district_id'=>$request->district_id,
            'village_name' => $request->village_name,
        ]);
        return (New VillageResponse($data))
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
        $data = Village::with(['district'])->findOrFail($id);
        return (New VillageResponse($data))
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
    public function update(VillageRequest $request, $id)
    {
        $validate = $request->validated();

        $data = Village::with("district")->findOrFail($id);
        if($data->update($validate)){
            return (New VillageResponse($data))
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
        $data = Village::with("district")->findOrFail($id);
        if($data->delete()){
            return (New VillageResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
