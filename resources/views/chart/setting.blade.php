@extends('layouts.master')

@section('css')
<link href="{{ asset('css/chart.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/bootstrap-multiselect.min.css') }}" rel="stylesheet">
<style type="text/css">
	.dropdown-item.active, .dropdown-item:active {
		color: unset;
		background-color: unset;
	}
	.custom-select {
		font-size: 0.7875rem;
	}
</style>
@endsection

@section('content')
  
<!-- Page header -->
<p class="h1 mt-3">Setting chart</p>

<!-- add tab header here -->

<!--content-->
<div class="row mb-4">
	<div class="col-md">
		<div class="card">
			<div class="card-body">
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="bar">Column</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="horizontalBar">Bar</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="line">Line</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="area">Area</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="pie">Pie</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="doughnut">Doughnut</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md">
		<div class="card">
			<div class="card-header">
				Setting
			</div>
			<div class="card-body">
				<form>
					<div class="form-group row mb-1">
						<label for="selectx" class="col-sm-3 col-form-label form-control-sm">X Axis</label>
						<div class="col-sm-9">
							<select class="form-control form-control-sm" id="selectx">
								@foreach($columns as $col)
					            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
					            @endforeach
							</select>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label for="selecty" class="col-sm-3 col-form-label form-control-sm">Data</label>
						<div class="col-sm-9">
							<select class="form-control form-control-sm" id="selecty" name="selecty" multiple="multiple">
								@foreach($columns as $col)
									@if($col['type'] == 'numeric')
					            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
					            	@endif
					            @endforeach
							</select>
						</div>
					</div>
					<div class="form-group row mb-1">
						<!-- <label for="data-label" class="col-sm-3 col-form-label form-control-sm"></label> -->
						<div class="col-sm-12">
							<div class="custom-control custom-switch">
		                        <input type="checkbox" class="custom-control-input" id="chk-judul" checked="checked">
		                        <label class="custom-control-label form-control-sm" for="chk-judul">Tampilkan judul di grafik</label>
		                    </div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label for="data-label" class="col-sm-3 col-form-label form-control-sm">Judul</label>
						<div class="col-sm-9">
							<input class="form-control form-control-sm" type="text" placeholder="[Isi judul grafik disini]" id="data-label">
						</div>
					</div>
					<div class="form-group row mb-1">
						<!-- <label for="data-label" class="col-sm-3 col-form-label form-control-sm">Legend</label> -->
						<div class="col-sm-12">
							<div class="custom-control custom-switch">
		                        <input type="checkbox" class="custom-control-input" id="chk-legend" checked="checked">
		                        <label class="custom-control-label form-control-sm" for="chk-legend">Tampilkan legend di grafik</label>
		                    </div>
						</div>
					</div>
					<div class="form-group row mb-1">
						<label for="legend-pos" class="col-sm-3 col-form-label form-control-sm">Posisi Legend</label>
						<div class="col-sm-9">
							<select class="form-control form-control-sm" id="legend-pos">
					            <option value="right">Kanan</option>
					            <option value="top">Atas</option>
					            <option value="left">Kiri</option>
					            <option value="bottom">Bawah</option>
							</select>
						</div>
					</div>
				</form>
				<button type="button" class="btn btn-primary btn-sm btn-chart-preview float-right mt-3">Pratinjau</button>
			</div>
		</div>
	</div>
	<div class="col-md">
		<div class="card">
			<div class="card-header">
				Pratinjau
			</div>
			<div class="card-body">
				<canvas id="chart-preview"></canvas>
			</div>
		</div>
	</div>
</div>

<button type="button" class="btn btn-primary" id="prev-process">Kembali</button>
<button type="button" class="btn btn-primary" id="next-process">Tampilkan</button>

<div style="display: none">
	<form id="submit-form" action="{{ route('chart.setting.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	    <input type="hidden" name="code" id="code" value="{{ $code }}">
	    <input type="text" name="options" id="options" value="">
	</form>
</div>
@endsection


@section('js')
	@include('chart.partials.setting_js')
@endsection