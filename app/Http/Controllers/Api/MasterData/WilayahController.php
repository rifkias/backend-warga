<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\Wilayah;
use App\Http\Resources\MasterData\WilayahResponse;
use App\Http\Requests\MasterData\WilayahRequest;
class WilayahController extends Controller
{
    public function index(Request $request)
    {
        $data = Wilayah::query();
        $per_page = 10;
        if($request->filled("per_page")){
            $per_page = $request->per_page;
        }
        if($request->sort_field && $request->sort_type){
            $data = $data->orderBy($request->sort_field,$request->sort_type);
        }
        if($request->filled('rt')){
            $data =  $data->where("rt","LIKE","%".$request->rt."%");
        }
        if($request->filled('rw')){
            $data =  $data->where("rw","LIKE","%".$request->rw."%");
        }
        if($request->filled('kelurahan')){
            $data =  $data->where("kelurahan","LIKE","%".$request->kelurahan."%");
        }
        if($request->filled('kecamatan')){
            $data =  $data->where("kecamatan","LIKE","%".$request->kecamatan."%");
        }
        if($request->filled('kabupaten')){
            $data =  $data->where("kabupaten","LIKE","%".$request->kabupaten."%");
        }
        if($request->filled('provinsi')){
            $data =  $data->where("provinsi","LIKE","%".$request->provinsi."%");
        }
        if($request->filled('negara')){
            $data =  $data->where("negara","LIKE","%".$request->negara."%");
        }
        if($request->filled('kode_pos')){
            $data =  $data->where("kode_pos","LIKE","%".$request->kode_pos."%");
        }
        if($request->filled('jalan')){
            $data =  $data->where("jalan","LIKE","%".$request->jalan."%");
        }
        $data = $data->paginate($per_page);
        return (WilayahResponse::collection($data))
                ->response()
                ->setStatusCode(200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WilayahRequest $request)
    {
        $validate = $request->validated();
        $data = Wilayah::create($validate);
        return (New WilayahResponse($data))
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
        $data = Wilayah::findOrFail($id);
        return (New WilayahResponse($data))
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
    public function update(WilayahRequest $request, $id)
    {
        // return $request->all();


        $validate = $request->validated();

        $data = Wilayah::findOrFail($id);
        if($data->update($validate)){
            return (New WilayahResponse($data))
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
        $data = Wilayah::findOrFail($id);
        if($data->delete()){
            return (New WilayahResponse($data))
                ->response()
                ->setStatusCode(200);
        }
    }
}
