<?php

namespace App\Services;

use App\Imports\DataImport;
use App\Models\Datasource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\Filesystem;

class ChartService {
	
    private $default_file_name = 'datasource';
    private $default_path = 'file_repository/';
    private $data_file_name = 'json_datasource.json';
    private $chart_options_file_name = "chart_options.json";

    public function NewChart() 
    {
    	$code = Str::lower(Str::random(8));

    	return redirect()->route('chart.upload.get', [$code]);
    }

    public function ShowUpload(Request $request, $code)
    {
        $datasource = Datasource::all();
        return view('chart.upload', ['code' => $code, 'datasource' => $datasource]);
    }

    public function PrepareFolder($path)
    {
        if(file_exists($path)) {
            $fs = new Filesystem;
            $fs->cleanDirectory($path);
        }
        else {
            mkdir($path, 0777); 
        }
    }

    public function GetOriginFile($code)
    {
        $path = storage_path($this->default_path.$code.'/');
        $files = \File::glob($path.$this->default_file_name.'.*');

        if(count($files) <= 0) {
            $response = [
                'message' => 'File sumber data tidak ditemukan. Kemungkinan terjadi kesalahan saat mengunggah file sumber data',
                'errors' => 'Gagal membuka file sumber data!'
            ];

            abort( response()->json($response,400) );
        }

        return $files[0];
    }

    public function LoadOriginDataArray(Request $request, $code)
    {
        $file = $this->GetOriginFile($code);

        // convert data to array format
        $data = (new DataImport)->toArray($file)[0];

        return $data;
    }

    public function SaveDataAndRedirect($data,$code)
    {
        $path = storage_path($this->default_path.$code.'/');
        $data_file = $path.$this->data_file_name;
        file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

        return redirect()->route('chart.check.get', [$code]);
    }

    public function UploadFile(Request $request, $code)
    {
    	$file = $request->file('file');
        $extension = $file->getClientOriginalExtension();
        $filename = $this->default_file_name.'.'.$extension;
        $path = storage_path($this->default_path.$code.'/');

        $this->PrepareFolder($path);

        $file->move($path, $filename);

        $data = $this->LoadOriginDataArray($request, $code);

        //save to json
        $obj = [];
        if(count($data) > 0) {
            $columns = $data[0];
            // convert to json format
            for ($i=1; $i<count($data); $i++){
                $tmp = [];
                foreach ($columns as $column_index => $column){
                    $tmp[$column] = $data[$i][$column_index];
                }
                array_push($obj,$tmp);
            }
        }

        $data = $obj;

        return $this->SaveDataAndRedirect($data,$code);
        //save data to file json
        // $data_file = $path.$this->data_file_name;
        // file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

        // return redirect()->route('chart.check.get', [$code]);
    }

    public function UploadDataset(Request $request, $code)
    {
        $path = storage_path($this->default_path.$code.'/');
        
        $this->PrepareFolder($path);

        $ds_id = $request->ds_id;
        $ds = Datasource::find($ds_id);
        $data = DB::select($ds->query);

        return $this->SaveDataAndRedirect($data,$code);
        //save data to file json
        // $data_file = $path.$this->data_file_name;
        // file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

        // return redirect()->route('chart.check.get', [$code]);
    }

    public function ManualData(Request $request, $code)
    {
        $data = json_decode($request->data);
        $header_included = $request->header;

        $obj = [];
        if(count($data) > 0) {

            $columns = [];
            if (boolval($header_included)) $columns = $data[0];
            else {
                $i = 1;
                foreach($data[0] as $d) {
                    $kolom = 'Header '.($i++);
                    array_push($columns,$kolom);
                }
            }

            // convert to json format
            $start = (boolval($header_included)) ? 1 : 0;
            for ($i=$start; $i<count($data); $i++){
                $tmp = [];
                foreach ($columns as $column_index => $column){
                    $tmp[$column] = $data[$i][$column_index];
                }
                array_push($obj,$tmp);
            }
        }

        $data = $obj;

        return $this->SaveDataAndRedirect($data,$code);
        //save data to file json
        // $data_file = $path.$this->data_file_name;
        // file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

        // return redirect()->route('chart.check.get', [$code]);
    }

    public function UploadData(Request $request, $code)
    {
        $tab = $request->tab;

        if($tab == 'nav-upload') return $this->UploadFile($request, $code);
        if($tab == 'nav-dataset') return $this->UploadDataset($request, $code);
        if($tab == 'nav-manual') return $this->ManualData($request, $code);

    }

    public function GetCheckData(Request $request, $code)
    {
        $path = storage_path($this->default_path.$code.'/');
        $data_file = $path.$this->data_file_name;

    }

    public function SaveCheckedData(Request $request, $code)
    {
    	$str_columns = $request->columns;
    	$columns = explode(",",$str_columns);
    	$data = json_decode($request->data);

		$obj = [];
		if(count($data) > 0) {
			// convert to json format
			for ($i=1; $i<count($data); $i++){
				$tmp = [];
				foreach ($columns as $column_index => $column){
					$tmp[$column] = $data[$i][$column_index];
				}
				array_push($obj,$tmp);
			}
		}

		$data = $obj;

		//save data to file json
    	$path = storage_path($this->default_path.$code.'/');
		$data_file = $path.$this->data_file_name;
    	
		file_put_contents($data_file, json_encode($data, JSON_PRETTY_PRINT));

    	// return compact('columns','data');
    	return redirect()->route('chart.setting.get', [$code]);
    }

    public function SaveChartOptions(Request $request, $code) 
    {
    	$path = storage_path($this->default_path.$code.'/');
		$setting_file = $path.$this->chart_options_file_name;
		
		$chart_options = $request->options;
		file_put_contents($setting_file, $chart_options);

    	return redirect()->route('chart.publish', [$code]);
    }

    public function GetChartData($code)
    {
    	$path = storage_path($this->default_path.$code.'/');
		$data_file = $path.$this->data_file_name;
		$data = json_decode(file_get_contents($data_file), true);

		return $data;
    }

    public function GetChartOptions($code)
    {
    	$path = storage_path($this->default_path.$code.'/');
    	$setting_file = $path.$this->chart_options_file_name;
    	$chart_settings = json_decode(file_get_contents($setting_file), true);

    	return $chart_settings;
    }

}