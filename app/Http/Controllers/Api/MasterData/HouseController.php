<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\House;
use App\Http\Resources\MasterData\HouseResponse;
use App\Http\Requests\MasterData\HouseRequest;

class HouseController extends Controller
{
    public function index(Request $request)
    {
        $data = House::with(['wilayah']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has('house_no')){
            $data =  $data->where("house_no","LIKE","%".$request->house_no."%");
        }
        if($request->has('jalan')){
            $data =  $data->where("jalan","LIKE","%".$request->jalan."%");
        }
        if($request->has('wilayah_id')){
            $data =  $data->where("wilayah_id",$request->wilayah_id);
        }
        $data = $data->paginate($per_page);
        return (HouseResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HouseRequest $request)
    {
        $validate = $request->validated();
        $data = House::with("wilayah")->create($validate);
        return (New HouseResponse($data))
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
        $data = House::with("wilayah")->findOrFail($id);
        return (New HouseResponse($data))
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
    public function update(HouseRequest $request, $id)
    {
        // return $request->all();


        $validate = $request->validated();

        $data = House::with("wilayah")->findOrFail($id);
        if($data->update($validate)){
            return (New HouseResponse($data))
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
        $data = House::with("wilayah")->findOrFail($id);
        if($data->delete()){
            return (New HouseResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
