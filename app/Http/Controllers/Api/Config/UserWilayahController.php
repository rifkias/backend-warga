<?php

namespace App\Http\Controllers\Api\Config;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Config\UserWilayah;
use App\Http\Resources\Config\UserWilayahResponse;
use App\Http\Requests\Config\UserWilayahRequest;

class UserWilayahController extends Controller
{
    public function index(Request $request)
    {
        $data = UserWilayah::with(['user','wilayah']);
        $per_page = 10;
        if($request->has("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->has("user_id")){
            $data = $data->where("user_id",$request->user_id);
        }
        if($request->has("wilayah_id")){
            $data = $data->where("wilayah_id",$request->wilayah_id);
        }
        $data = $data->paginate($per_page);
        return (UserWilayahResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserWilayahRequest $request)
    {
        $validate = $request->validated();

        $data = UserWilayah::create($validate);
        return (New UserWilayahResponse($data))
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
        $data = UserWilayah::with(['wilayah','user'])->findOrFail($id);
        return (New UserWilayahResponse($data))
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
    public function update(UserWilayahRequest $request, $id)
    {
        $validate = $request->validated();

        $data = UserWilayah::with(['wilayah','user'])->findOrFail($id);
        if($data->update($validate)){
            return (New UserWilayahResponse($data))
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
        $data = UserWilayah::with(['wilayah','user'])->findOrFail($id);
        if($data->delete()){
            return (New UserWilayahResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
