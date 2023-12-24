<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterData\Wilayah;
use App\Http\Resources\MasterData\WilayahResponse;
use App\Http\Requests\MasterData\WilayahRequest;
use App\Http\Controllers\ApiLogController as ApiLog;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

class WilayahController extends Controller
{
    protected $apiLog;
    public function __construct()
    {
        $this->apiLog           = new ApiLog;
    }
    public function index(Request $request)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pread');
        if ($checkPermission) {
            $data = Wilayah::query();
            $per_page = 10;
            $download = $request->download;
            if ($request->filled("per_page")) {
                $per_page = $request->per_page;
            }
            if ($request->sort_field && $request->sort_type) {
                $data = $data->orderBy($request->sort_field, $request->sort_type);
            }
            if ($request->filled('rt')) {
                $data =  $data->where("rt", "LIKE", "%" . $request->rt . "%");
            }
            if ($request->filled('rw')) {
                $data =  $data->where("rw", "LIKE", "%" . $request->rw . "%");
            }
            if ($request->filled('kelurahan')) {
                $data =  $data->where("kelurahan", "LIKE", "%" . $request->kelurahan . "%");
            }
            if ($request->filled('kecamatan')) {
                $data =  $data->where("kecamatan", "LIKE", "%" . $request->kecamatan . "%");
            }
            if ($request->filled('kabupaten')) {
                $data =  $data->where("kabupaten", "LIKE", "%" . $request->kabupaten . "%");
            }
            if ($request->filled('provinsi')) {
                $data =  $data->where("provinsi", "LIKE", "%" . $request->provinsi . "%");
            }
            if ($request->filled('negara')) {
                $data =  $data->where("negara", "LIKE", "%" . $request->negara . "%");
            }
            if ($request->filled('kode_pos')) {
                $data =  $data->where("kode_pos", "LIKE", "%" . $request->kode_pos . "%");
            }
            if ($request->filled('jalan')) {
                $data =  $data->where("jalan", "LIKE", "%" . $request->jalan . "%");
            }

            if ($download == "download") {
                $response    = $data->get();
                return $this->downloadData($response);
            } else {
                $data = $data->paginate($per_page);
                return (WilayahResponse::collection($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(WilayahRequest $request)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pcreate');
        if ($checkPermission) {
            $validate = $request->validated();
            $data = Wilayah::create($validate);
            return (new WilayahResponse($data))
                ->response()
                ->setStatusCode(201);
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pread');
        if ($checkPermission) {
            $data = Wilayah::findOrFail($id);
            return (new WilayahResponse($data))
                ->response()
                ->setStatusCode(200);
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
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

        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pread');
        if ($checkPermission) {
            $validate = $request->validated();

            $data = Wilayah::findOrFail($id);
            if ($data->update($validate)) {
                return (new WilayahResponse($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
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
        $checkPermission    = $this->apiLog->checkPermission(class_basename(get_class($this)), 'pdelete');
        if ($checkPermission) {
            $data = Wilayah::findOrFail($id);
            if ($data->delete()) {
                return (new WilayahResponse($data))
                    ->response()
                    ->setStatusCode(200);
            }
        } else {
            return response()->json([
                'error' => 'Forbidden access'
            ], 403);
        }
    }

    private function downloadData($datas)
    {
        set_time_limit(0);
        error_reporting(0);
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 0);

        $name           = uniqid() . ".xlsx";
        $file_path      = storage_path('download') . '/' . $name;
        $spreadsheet    = new Spreadsheet();
        $sheet          = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'RT');
        $sheet->setCellValue('B1', 'RW');
        $sheet->setCellValue('C1', 'Kecamatan');
        $sheet->setCellValue('D1', 'Kelurahan');
        $sheet->setCellValue('E1', 'Kabupaten');
        $sheet->setCellValue('F1', 'Provinsi');
        $sheet->setCellValue('G1', 'Kode Pos');
        $sheet->setCellValue('H1', 'Created At');
        $sheet->setCellValue('I1', 'Updated At');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $x  = 2;
        if (count($datas) > 0) {
            foreach ($datas as $data) {
                $sheet->setCellValue('A' . $x, @$data->rt);
                $sheet->setCellValue('B' . $x, @$data->rw);
                $sheet->setCellValue('C' . $x, @$data->kecamatan);
                $sheet->setCellValue('D' . $x, @$data->kelurahan);
                $sheet->setCellValue('E' . $x, @$data->kabupaten);
                $sheet->setCellValue('F' . $x, @$data->provinsi);
                $sheet->setCellValue('G' . $x, @$data->kode_pos);
                $sheet->setCellValue('H' . $x, @$data->created_at);
                $sheet->setCellValue('I' . $x, @$data->updated_at);
                $x++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save($file_path);
        if (file_exists($file_path)) {
            $file = file_get_contents($file_path);
            $res = response($file, 200)->withHeaders(['Content-Type' => 'application/vnd.ms-excel', 'Content-Disposition' => 'attachment;filename="' . $name . '"']);
            register_shutdown_function('unlink', $file_path);
            return $res;
        } else {
            return response()
                ->json(['status' => 500, 'datas' => null, 'errors' => ['location_id' => 'download file error']])
                ->withHeaders([
                    'Content-Type'          => 'application/json',
                ])
                ->setStatusCode(500);
        }
    }
}
