<?php

namespace App\Http\Controllers;

use App\Services\ChartService;
use Illuminate\Http\Request;
// use App\Imports\DataImport;
// use Illuminate\Support\Str;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Filesystem\Filesystem;

class ChartController extends Controller
{
	private $service; 

    public function __construct()
    {
        $this->service = new ChartService();
    }

    public function create() 
    {
    	return $this->service->NewChart();
    }

    public function upload(Request $request, $code)
    {
    	if ($request->isMethod('get')) return $this->service->ShowUpload($request, $code);

    	return $this->service->UploadData($request, $code);
    }

    public function check(Request $request, $code)
    {
    	if ($request->isMethod('post')) return $this->service->SaveCheckedData($request, $code);

  //   	$data = $this->service->LoadOriginDataArray($request, $code);
    	$data = $this->service->GetChartData($code);

    	$columns = [];
		if(count($data) > 0) {
			$head = array_keys($data[0]);
			$sample = array_values($data[0]);

			for($i = 0; $i < count($head); $i++) {
				$tmp = [];
				$tmp['title'] = $head[$i];
				$tmp['type'] = (is_numeric($sample[$i])) ? 'numeric' : 'text';

				array_push($columns,$tmp);
			}
		}

    	// $data = array_slice($data,1);

    	return view('chart.check', compact('code','columns','data'));
    }

    public function setting(Request $request, $code)
    {
    	// $data = $this->service->LoadOriginDataJson($request, $code);
		$data = $this->service->GetChartData($code);
		$options = $this->service->GetChartOptions($code);

    	$columns = [];
		if(count($data) > 0) {
			$head = array_keys($data[0]);
			$sample = array_values($data[0]);

			for($i = 0; $i < count($head); $i++) {
				$tmp = [];
				$tmp['title'] = $head[$i];
				$tmp['type'] = (is_numeric($sample[$i])) ? 'numeric' : 'text';

				array_push($columns,$tmp);
			}
		}
		// return $columns;
    	// foreach($data[0] as $key => $val) {
    	// 	array_push($columns, $key);
    	// }

		return view('chart.setting', compact('code','columns','data','options'));
    }

    public function setting_post(Request $request, $code)
    {
    	return $this->service->SaveChartOptions($request, $code);
    }

    public function publish(Request $request, $code)
    {
		$chart_settings = $this->service->GetChartOptions($code);
		$data = $this->service->GetChartData($code);

		// return file_get_contents($setting_file);
		return view('chart.publish', compact('code','chart_settings','data'));
    }
}
