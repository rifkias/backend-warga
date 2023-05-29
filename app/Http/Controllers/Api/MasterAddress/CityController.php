<?php

namespace App\Http\Controllers\Api\MasterAddress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterAddress\City;
use App\Http\Resources\MasterAddress\CityResponse;
use App\Http\Requests\MasterAddress\CityRequest;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $data = City::with(['district','province']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('province_id')){
            $data = $data->where("province_id",$request->province_id);
        }
        if($request->has('city_name')){
            $data =  $data->where("city_name","LIKE","%".$request->city_name."%");
        }
        $data = $data->paginate($per_page);
        return (CityResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CityRequest $request)
    {
        $validate = $request->validated();

        $data = City::create([
            'province_id'=>$request->province_id,
            'city_name' => $request->city_name,
        ]);
        return (New CityResponse($data))
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
        $data = City::with(['district','province'])->findOrFail($id);
        return (New CityResponse($data))
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
    public function update(CityRequest $request, $id)
    {
        $validate = $request->validated();

        $data = City::with("district")->findOrFail($id);
        if($data->update($validate)){
            return (New CityResponse($data))
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
        $data = City::with("district")->findOrFail($id);
        if($data->delete()){
            return (New CityResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
