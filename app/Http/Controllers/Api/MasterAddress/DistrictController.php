<?php

namespace App\Http\Controllers\Api\MasterAddress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterAddress\District;
use App\Http\Resources\MasterAddress\DistrictResponse;
use App\Http\Requests\MasterAddress\DistrictRequest;

class DistrictController extends Controller
{
    public function index(Request $request)
    {
        $data = District::with(['village','city']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('city_id')){
            $data = $data->where("city_id",$request->city_id);
        }
        if($request->has('district_name')){
            $data =  $data->where("district_name","LIKE","%".$request->district_name."%");
        }
        $data = $data->paginate($per_page);
        return (DistrictResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DistrictRequest $request)
    {
        $validate = $request->validated();

        $data = District::create([
            'city_id'=>$request->city_id,
            'district_name' => $request->district_name,
        ]);
        return (New DistrictResponse($data))
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
        $data = District::with(['city','village'])->findOrFail($id);
        return (New DistrictResponse($data))
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
    public function update(DistrictRequest $request, $id)
    {
        $validate = $request->validated();

        $data = District::with("village")->findOrFail($id);
        if($data->update($validate)){
            return (New DistrictResponse($data))
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
        $data = District::with("village")->findOrFail($id);
        if($data->delete()){
            return (New DistrictResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
