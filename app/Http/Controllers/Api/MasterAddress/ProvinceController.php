<?php

namespace App\Http\Controllers\Api\MasterAddress;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterAddress\Province;
use App\Http\Resources\MasterAddress\ProvinceResponse;
use App\Http\Requests\MasterAddress\ProvinceRequest;
class ProvinceController extends Controller
{
    public function index(Request $request)
    {
        $data = Province::with('city');
        $per_page = 10;
        if($request->filled("per_page")){
            $per_page = $request->per_page;
        }
        if($request->filled('province_name')){
            $data =  $data->where("province_name","LIKE","%".$request->province_name."%");
        }
        if($request->filled("sort_field") && $request->filled("sort_type")){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        $data = $data->paginate($per_page);
        return (ProvinceResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProvinceRequest $request)
    {
        $validate = $request->validated();

        $data = Province::create([
            'province_name' => $request->province_name,
        ]);
        return (New ProvinceResponse($data))
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
        $data = Province::with('city')->findOrFail($id);
        return (New ProvinceResponse($data))
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
    public function update(ProvinceRequest $request, $id)
    {
        $validate = $request->validated();

        $data = Province::with("city")->findOrFail($id);
        if($data->update($validate)){
            return (New ProvinceResponse($data))
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
        $data = Province::with("city")->findOrFail($id);
        if($data->delete()){
            return (New ProvinceResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
