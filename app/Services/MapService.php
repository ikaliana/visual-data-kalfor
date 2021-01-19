<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;
use RarArchive;
use Shapefile\Shapefile;
use Shapefile\ShapefileException;
use Shapefile\ShapefileReader;

class MapService 
{
	public function listShapefile1($request, $code){
		$pathTemp1 = storage_path().'/shapefile';
		
		$listShapefile = array();
		$directories = glob($pathTemp1 . '/*' , GLOB_ONLYDIR);
		
		foreach ($directories as $dir) {
			$tmp=[];
			$tmp['nama']=basename($dir);
			$pathTemp2 = $dir."/dataSource.txt";
			$tmp['deskripsi']=file_get_contents($pathTemp2);
		    array_push($listShapefile,$tmp);
		}

		return view('map.source', compact('listShapefile','code'));
		// return $listShapefile;
	}

	public function shpUploader1(Request $request){

		//store data ke variabel
		$file1 = $request->file('fileShp');
		$namaFile1 = $request->input('namaFile');
		$deskripsi1 = $request->input('deskripsi');
		$ext1 = $file1->guessExtension();
      	$pathTemp1 = storage_path().'/shapefile/'.$namaFile1.'/';
      	
      	//make file directory (untuk menampung file shp,shx, dll <-- extract dari zip)
      	if(file_exists($pathTemp1)) {
            $pesan= '<h5><span class="badge badge-warning">Gagal</span></h5><span>Maaf, nama file sudah tersedia,mohon ganti nama filenya.</span>';
            return $pesan;
        }
        else {
            File::makeDirectory($pathTemp1, 0775, true, true);
        }

        //extract file ke folder temporary
        if ($ext1=="zip") {
        	$zip = new ZipArchive;
			$res = $zip->open($file1);
			if ($res === TRUE) {
				$zip->extractTo($pathTemp1);
			  	$zip->close();
			} else {
			  	$pesan= '<h5><span class="badge badge-warning">Gagal</span></h5><span>Maaf, file gagal terupload. Silahkan ulang proses atau hubungi administrator. tes tes</span>';
			  	return $pesan;
			}
        } else {
        	$pesan= '<h5><span class="badge badge-warning">Gagal</span></h5><span>Maaf, file yang anda upload bukan file compresi (zip/rar).</span>';
		  	exit($pesan);
        }

        //rename semua file di folder untuk dipake buat shapefile (menanggulangi pemakaian spasi pada nama file)
        $files1 = File::files($pathTemp1);
        foreach($files1 as $path) { 
	        $file = pathinfo($path);
	        $ext = $file['extension'];
	        $oldPath = $pathTemp1.$file['filename'].'.'.$ext;
	        $newPath = $pathTemp1.'dataSource'.'.'.strtolower($file['extension']);
	        File::move($oldPath, $newPath);
	    }

	    //store deksripsi ke file txt
	    File::put($pathTemp1.'dataSource.txt',$deskripsi1);

		//check minimum file SHP exist or not
		$fileShp1=$pathTemp1.'dataSource.shp';
		$fileShx1=$pathTemp1.'dataSource.shx';
		$fileDbf1=$pathTemp1.'dataSource.dbf';
		$filePrj1=$pathTemp1.'dataSource.prj';
		$fileTxt1=$pathTemp1.'dataSource.txt';
		
		if (File::exists($fileShp1) and File::exists($fileShx1) and File::exists($fileDbf1) and File::exists($filePrj1) and File::exists($fileTxt1)) {
			echo '<h5><span class="badge badge-success">File terupload</span></h5>';
		} else {
			if (!File::exists($pathTemp1)){
				//
			} else {
				File::deleteDirectory($pathTemp1);
			}
			echo '<h5><span class="badge badge-danger">Gagal</span></h5><span>Maaf file gagal terupload, salah satu file SHP/SHX/DBF tidak termuat dalam file kompresi (zip/rar) yang anda upload. Atau mungkin file anda mengandung folder. Mohon cek isi file anda dan upload file compresi (zip/rar) tanpa folder.</span>';
		}
	}

    public function listField1(Request $request, $code) {
    	$key = "shapedata.".$code;
    	
    	if(!session()->has($key)) return redirect()->route('map.source.list', [$code]);

    	$data = session()->get($key);
    	$data = json_decode($data);

    	// return json_decode($data);

    	$dataset = [];

    	foreach($data as $folder) {
	        try {
	        	$fields1=[];
	        	$filename = storage_path().'/shapefile/'.$folder.'/dataSource.shp';
	            
	            $Shapefile = new ShapefileReader($filename,[
	                    Shapefile::OPTION_POLYGON_CLOSED_RINGS_ACTION => Shapefile::ACTION_IGNORE,
	                    Shapefile::OPTION_SUPPRESS_M => true,
	                    Shapefile::OPTION_SUPPRESS_Z => true,
	            ]);

	            //hanya ambil nama, kalau ambil nama dan type fungsinya=getField;
	            $namaFields1 = $Shapefile->getFieldsNames();
	            $tmpNum=[]; //numerik
	            $tmpNon=[]; //non
	                
	            foreach($namaFields1 as $nf1) {
	                $tipe = $Shapefile->getFieldType($nf1); //ambil tipe field
	                
	                if (($tipe=='F') or ($tipe=='N')){ //ambil field yang numerik doang
	                    array_push($tmpNum,$nf1);
	                } elseif (($tipe=='C') or ($tipe=='D')) { //ambil field yang char/date doang
	                    array_push($tmpNon,$nf1);
	                }
	            }

	            $fields1['folder']=$folder;
	            $fields1['num']=$tmpNum;
	            $fields1['non']=$tmpNon;

	            array_push($dataset,$fields1);

	        } catch (ShapefileException $e) {
	            // Print detailed error information
	            $pesan= "Error Type: " . $e->getErrorType()
	                    . "\nMessage: " . $e->getMessage()
	                    . "\nDetails: " . $e->getDetails();
	            return $pesan;
	        }
    	}

    	// return $dataset;
        return view('map.setting', compact('dataset','code'));
    }

    public function agregat(Request $request,$code) {
    	
    	$key = "aggregatdata.".$code;
    	
    	if(!session()->has($key)) return redirect()->route('map.source.list', [$code]);

    	$data = session()->get($key);
    	$data = json_decode($data);

    	$dataset = [];

    	foreach ($data as $d)
    	{
            try {

                $fields1=[];
                $foldername1 = $d->folder;
                $kolom1 = $d->kolom;
                $group1 = $d->group;
                $totals1 = array();

                $filename1 = storage_path().'/shapefile/'.$foldername1.'/dataSource.shp';
                $Shapefile1 = new ShapefileReader($filename1,[
                        Shapefile::OPTION_POLYGON_CLOSED_RINGS_ACTION => Shapefile::ACTION_IGNORE,
                        Shapefile::OPTION_SUPPRESS_M => true,
                        Shapefile::OPTION_SUPPRESS_Z => true,
                ]);
                $datas1=array();
                
                while ($data = $Shapefile1->fetchRecord()) {
                    $datas1[]=$data->getDataArray();
                }
                // begin the iteration for grouping name and calculate the total
                
                //echo $group;
                if ($group1) {
                    
                    //$formatArray = array($group=>"",$kolom=>null);
                    foreach($datas1 as $data) {
                        $index = $this->group_exists($group1, $data[$group1], $totals1);
                        if ($index < 0) {
                            $totals1[] = $data;
                        }
                        else {
                            $totals1[$index][$kolom1] +=  $data[$kolom1];
                        }
                    }
                    //return $totals1;
                } else {
                    $totals1 = array($kolom1=>null);
                    foreach($datas1 as $data) {
                            $totals1[$kolom1] +=  $data[$kolom1];
                    }
                    //return $totals1;
                }

	            $fields1['folder']=$foldername1;
	            $fields1['kolom']=$kolom1;
	            $fields1['group']=$group1;
	            $fields1['total']=$totals1;

	            array_push($dataset,$fields1);

            } catch (ShapefileException $e) {
                // Print detailed error information
                $pesan= "Error Type: " . $e->getErrorType()
                        . "\nMessage: " . $e->getMessage()
                        . "\nDetails: " . $e->getDetails();
                return $pesan;
            }
    	}

        // return $dataset;
        return view('map.publish', compact('dataset','code'));
    }

    public function group_exists($group, $groupname, $array) {
        $result = -1;
        for($i=0; $i<sizeof($array); $i++) {
            if ($array[$i][$group] == $groupname) {
                $result = $i;
                break;
            }
        }
        return $result;
    }

    public function geojson(Request $request) {
        $foldername = $request->input('foldername');

        try {

            $filename1 = storage_path().'/shapefile/'.$foldername.'/dataSource.shp';
            //return $filename1;
            $Shapefile = new ShapefileReader($filename1,[
                    Shapefile::OPTION_POLYGON_CLOSED_RINGS_ACTION => Shapefile::ACTION_IGNORE,
                    Shapefile::OPTION_SUPPRESS_M => true,
                    Shapefile::OPTION_SUPPRESS_Z => true,
            ]);

            $totalRecord = $Shapefile->getTotRecords();
            $nRecord=0;
            $geojson="";
            // Read all the records
            while ($Geometry = $Shapefile->fetchRecord()) {
                $nRecord++;
                // Skip the record if marked as "deleted"
                if ($Geometry->isDeleted()) {
                    continue;
                }
                if ($nRecord<>$totalRecord) {
                    if ($nRecord==1){
                        $geojson = '['.$geojson.$Geometry->getGeoJSON(false,false).',';
                    } else {
                        $geojson = $geojson.$Geometry->getGeoJSON(false,false).',';
                    }
                } else {
                    $geojson = $geojson.$Geometry->getGeoJSON(false,false).']';
                }
            }
            return $geojson;

        } catch (ShapefileException $e) {
            // Print detailed error information
            $pesan= "Error Type: " . $e->getErrorType()
                    . "\nMessage: " . $e->getMessage()
                    . "\nDetails: " . $e->getDetails();
            return $pesan;
        }
    }

    public function getLegend(){
        $json = file_get_contents('http://geoportal.menlhk.go.id/arcgis/rest/services/KLHK/Penetapan_Kawasan_Hutan/MapServer/legend?f=pjson');
        return $json;
    }
}