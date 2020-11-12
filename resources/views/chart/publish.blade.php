@extends('layouts.master')

@section('css')
<link href="{{ asset('css/chart.min.css') }}" rel="stylesheet">
@endsection

@section('content')
  
<!-- Page header -->
<!-- <p class="h1 mt-3">Chart</p> -->

<!-- add tab header here -->

<!--content-->
<div class="row mb-3 mt-3">
	<div class="col-md">
		<div class="card border-secondary">
			<div class="card-header">
				<!-- <p class="h4 d-inline">Chart</p> -->
				<div class="float-right">
					<a download="chart.png" class="btn btn-sm btn-primary btn-save">Download</a>
					<button type="button" class="btn btn-sm btn-primary btn-setting">Ubah setting</button>
					<button type="button" class="btn btn-sm btn-primary btn-data">Ubah data</button>
				</div>
			</div>
			<div class="card-body">
				<canvas id="chart-preview"></canvas>
			</div>
			<!-- <div class="card-footer text-center">
				<a download="chart.png" class="btn btn-primary btn-save">Download</a>
				<button type="button" class="btn btn-primary btn-setting">Ubah setting</button>
				<button type="button" class="btn btn-primary btn-data">Ubah data</button>
			</div> -->
		</div>
	</div>
	<div class="col-md-3 single-stats">
		<div class="card mb-3 border-secondary">
			<div class="card-header font-weight-bold">
				Statistik <span id="data-label"></span>
			</div>
			<ul class="list-group list-group-flush">
				<li class="list-group-item d-flex justify-content-between align-items-center col-md ">
					Max <span class="badge max-value badge-light text-primary">MAX</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center col-md">
					Min <span class="badge min-value badge-light text-primary">MIN</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center col-md">
					Mean <span class="badge mean-value badge-light text-primary">MEAN</span>
				</li>
				<li class="list-group-item d-flex justify-content-between align-items-center col-md">
					Median <span class="badge median-value badge-light text-primary">MEDIAN</span>
				</li>
			</ul>
		</div>
	</div>
</div>
<div class="multi-stats row row-cols-md-2"></div>

<div id="template-multi-stats" class="d-none">
	<div class="col mb-3">
		<div class="card border-secondary">
			<div class="card-header font-weight-bold">
				Statistik <span id="data-label">STATLABEL</span>
			</div>
			<ul class="list-group list-group-horizontal list-group-flush">
				<li class="list-group-item border-0">
					Max <span class="badge badge-light ml-1"><h6 class="mb-0 text-primary">STATMAX</h6></span>
				</li>
				<li class="list-group-item border-0">
					Min <span class="badge badge-light ml-1"><h6 class="mb-0 text-primary">STATMIN</h6></span>
				</li>
				<li class="list-group-item border-0">
					Mean <span class="badge badge-light ml-1"><h6 class="mb-0 text-primary">STATMEAN</h6></span>
				</li>
				<li class="list-group-item border-0">
					Median <span class="badge badge-light ml-1"><h6 class="mb-0 text-primary">STATMEDIAN</h6></span>
				</li>
			</ul>		
		</div>
	</div>
</div>

@endsection


@section('js')
<script src="{{ asset('js/chart.min.js') }}"></script>
<script type="text/javascript">
	var arr = {
		max: function(array) {
			return Math.max.apply(null, array);
		},
		min: function(array) {
			return Math.min.apply(null, array);
		},
		sum: function(array) {
			var num = 0;
			for (var i = 0, l = array.length; i < l; i++) num += eval(array[i]);
			return num;
		},
		mean: function(array) {
			var num = arr.sum(array) / array.length;
			// return num;
			return Math.round(num * 100) / 100;
			//return (arr.sum(array) / array.length).toFixed(2);
		},
		median: function(array) {
			array.sort(function(a, b) { return eval(a) - eval(b); });
			var mid = array.length / 2;
			return mid % 1 ? eval(array[mid - 0.5]) : (eval(array[mid - 1]) + eval(array[mid])) / 2;
		},
	};
</script>

<script type="text/javascript">
	var options = JSON.parse('@json($chart_settings)');

	var chart_type = options.type;
	var ds = options.data.datasets;

	if(ds.length == 1) {
		$(".single-stats").show();
		$(".multi-stats").hide();
	}
	else {
		$(".single-stats").hide();
		$(".multi-stats").show();
	}

	if (chart_type == "scatter") {
		var ds_copy = {};

		$.each(ds,function(i,d) {
			var label_x = d.label_source[0];
			if((typeof ds_copy[label_x]) === "undefined") {
				ds_copy[label_x] = d.data.map(a => a.x);
			}

			var label_y = d.label_source[1];
			if((typeof ds_copy[label_y]) === "undefined") {
				ds_copy[label_y] = d.data.map(a => a.y);
			}
		});

		$.each(ds_copy,function(i,d) {
			var tmp = $("#template-multi-stats").html();
			var tmpdata = [...d];

			tmp = tmp.replace("STATLABEL",i);
			tmp = tmp.replace("STATMAX",arr.max(tmpdata));
			tmp = tmp.replace("STATMIN",arr.min(tmpdata));
			tmp = tmp.replace("STATMEAN",arr.mean(tmpdata));
			tmp = tmp.replace("STATMEDIAN",arr.median(tmpdata));

			$(".multi-stats").append(tmp);
		});
	}
	else {
		if(ds.length == 1) {
			$("#data-label").html(ds[0].label);

			var tmpdata = [...ds[0].data];
			$(".max-value").html(arr.max(tmpdata));
			$(".min-value").html(arr.min(tmpdata));
			$(".mean-value").html(arr.mean(tmpdata));
			$(".median-value").html(arr.median(tmpdata));
		}
		else {
			$.each(ds,function(i,d) {
				var tmp = $("#template-multi-stats").html();
				var tmpdata = [...d.data];
				// console.log(tmpdata);
				tmp = tmp.replace("STATLABEL",d.label);
				tmp = tmp.replace("STATMAX",arr.max(tmpdata));
				tmp = tmp.replace("STATMIN",arr.min(tmpdata));
				tmp = tmp.replace("STATMEAN",arr.mean(tmpdata));
				tmp = tmp.replace("STATMEDIAN",arr.median(tmpdata));

				$(".multi-stats").append(tmp);
			});
		}
	}

	var myChart;
	$(document).ready(function() {
		myChart = new Chart(document.getElementById("chart-preview"), options);
	});
</script>

<script type="text/javascript">
	$(".btn-save").on("click", function() {
        $(this).attr("href",myChart.toBase64Image());
    });

	$(".btn-setting").on("click", function() {
        location.href = "{{ route('chart.setting.get', ['code' => $code]) }}";
    });

	$(".btn-data").on("click", function() {
        location.href = "{{ route('chart.check.get', ['code' => $code]) }}";
    });
</script>
@endsection