<?php

namespace App\Http\Controllers;

use App\Services\MapService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MapController extends Controller
{
	private $service; 

    public function __construct()
    {
        $this->service = new MapService();
    }

    public function create()
    {
    	$code = Str::lower(Str::random(8));
    	return redirect()->route('map.source.list', [$code]);
    }

    public function source(Request $request, $code)
    {
    	if ($request->isMethod('get')) return $this->service->listShapefile1($request, $code);

    	$key = "shapedata.".$code;
    	session()->put($key, $request->data);

    	return redirect()->route('map.setting.get', [$code]);
    	//return $request->data;
    }

    public function upload(Request $request)
    {
    	$this->service->shpUploader1($request);
    }

    public function setting(Request $request, $code)
    {
    	if ($request->isMethod('get')) return $this->service->listField1($request,$code);

    	$key = "aggregatdata.".$code;
    	session()->put($key, $request->data);

    	return redirect()->route('map.publish', [$code]);
    }

    public function publish(Request $request, $code)
    {
    	return $this->service->agregat($request,$code);
    }

    public function geojson(Request $request)
    {
    	return $this->service->geojson($request);
    }

    public function legend(Request $request)
    {
    	return $this->service->getLegend();
    }

    public function download(Request $request)
    {
        return $this->service->download($request);
    }
}
