@extends('Layouts.master_fluid')

@section('css')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="crossorigin=""/>
<link rel="stylesheet" type="text/css" href="{{ asset('css/styleMap.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/spin.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/L.Control.BetterScale.css') }}">

@endsection


@section('content')

<div class="row">
    <div id="map1" class="col-9"></div>

    <div class="col-3 map-table">
        @foreach ($dataset as $data)

        @php
        	$group1 = $data["group"];
        	$kolom1 = $data["kolom"];
        	$totals1 = $data["total"];
        @endphp

        <h6 class="mt-3">Agregat dataset {{ $data["folder"] }}</h6>
        <table class="table table-striped mb-4">
            <thead>
                <tr>
                    <th>{{ $group1 }}</th>
                    <th>{{ $kolom1 }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($totals1 as $total1)
                    <tr>
                        <td>{{ $total1[$group1] }}</td>
                        <td align="right">{{ number_format($total1[$kolom1],2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endforeach

    </div>
</div>

@endsection


@section('js')

<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js" integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="crossorigin=""></script>
<script src="https://unpkg.com/esri-leaflet@2.3.3/dist/esri-leaflet.js" integrity="sha512-cMQ5e58BDuu1pr9BQ/eGRn6HaR6Olh0ofcHFWe5XesdCITVuSBiBZZbhCijBe5ya238f/zMMRYIMIIg1jxv4sQ==" crossorigin=""></script>
<script src="{{ asset('js/easyprint.js') }}"></script>
<script src="{{ asset('js/spin.min.js') }}"></script>
<script src="{{ asset('js/leaflet.spin.min.js') }}"></script>
<script src="{{ asset('js/L.Control.BetterScale.js') }}"></script>
<script src="{{ asset('js/leaflet.pattern.js') }}"></script>

<script type="text/javascript">
    var dataset = @json($dataset);
    var baseColors = ["Orange","Green","Fuchsia","DeepPink","Crimson","Gold","Maroon","NavajoWhite","DeepSkyBlue","MediumBlue","Silver","GreenYellow"];
    var baseAngles = [null,90,45,135,null,90,45,135,null,90,45,135];

    var mymap = L.map('map1',{
        center:[-1.082579, 120.785453],
        zoom:5
    });

    mymap.spin(true);

    var baseLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://openstreetmap.org/copyright">OSM</a> | KLHK RI & Pi Area'
    }).addTo(mymap);

    //UPDATE---ini layer KLHK ambil dari tile image server
    
    var layerKLHK = L.esri.tiledMapLayer({
      url: 'http://geoportal.menlhk.go.id/arcgis/rest/services/KLHK/Penetapan_Kawasan_Hutan/MapServer/',
      maxZoom: 19
    });

    layerKLHK.setZIndex(1);
    layerKLHK.addTo(mymap);

    var baseMaps = { "OpenStreetMap": baseLayer };
    var overlayMaps  = { "Kawasan Hutan": layerKLHK };
    var cIndex = 0;
    
    $.each(dataset, function(i,data) {
    	
    	mymap.spin(true);
        var layer1 = new L.geoJSON().addTo(mymap);
        overlayMaps[data.folder] = layer1;

        var stripes1 = new L.StripePattern({ color: baseColors[cIndex], angle: baseAngles[cIndex] });
        stripes1.addTo(mymap);

        var style1 = { "color": baseColors[cIndex++], "fillPattern": stripes1, "opacity": 0.5, "weight": 2 };

        $.getJSON("{{ route('map.geojson') }}", { foldername: data.folder }, function(result){

            L.geoJSON(result, { style: style1 }).addTo(layer1);

        }).done(function() {
            mymap.spin(false);
            mymap.fitBounds(layer1.getBounds());
                        
        }).fail(function(xhr, status, error) {
            mymap.spin(false);
            alert("Maaf, proses loading dataset "+ data.folder +" gagal.\r\n"+error);
        });
    });


    var legendControl = L.control.layers(baseMaps, overlayMaps, {position: 'topright'}).addTo(mymap);

    var legend = L.control({position: 'bottomleft'});
    var div = L.DomUtil.create('div', 'info legend');
    div.innerHTML ='<h6>Kawasan Pelepasan Hutan</h6><i style="background:green"></i>Seluruh Area<br><i style="background:orange"></i>Project Terpilih<hr>';

	$.getJSON("{{ route('map.legend') }}", function(result){
        //isi legend
        div.innerHTML +='<h6>Kawasan Hutan</h6>'
        for(var i=0;i<result.layers[0].legend.length;i++){
            div.innerHTML +='<i><img src="data:image/png;base64,'+result.layers[0].legend[i].imageData+'"></i>'+result.layers[0].legend[i].label+'<br>';
        }
    }).done(function() { 
    	mymap.spin(false); 
    }).fail(function(xhr, status, error) {
        mymap.spin(false);
        alert("Maaf, proses loading legend gagal.\r\n"+error);
    });

    legend.onAdd = function (mymap) { return div; };
    legend.addTo(mymap);

    var scale = L.control.betterscale({position: 'bottomright', imperial: false, metric: true});
    scale.addTo(mymap);

    var d = new Date();
	var nama = d.toLocaleString();
	nama=nama.replace("/", "");
	nama=nama.replace("/", "");
	nama=nama.replace(" ", "");
	nama=nama.replace(",", "");
	nama=nama.replace(":", "");
	nama=nama.replace(":", "");
	nama=nama.replace(" AM", "");
	nama=nama.replace(" PM", "");

	var printer = L.easyPrint({
		tileLayer: baseMaps,
		//sizeModes: ['Current'],
		filename: 'simpelk-'+nama,
		exportOnly: true
	}).addTo(mymap);
	
</script>

@endsection