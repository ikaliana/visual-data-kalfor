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
	.multiselect-native-select > .btn-group, .multiselect-container {
		width: 100%;
	}

	div[class^="scatter-row-"] {
		height: 35px;
	}
</style>
@endsection

@section('content')
  
<!-- Page header -->
<!-- <p class="h1 mt-3">Setting chart</p> -->
@include('chart.partials.progress')

<!--content-->
<div class="row mb-3">
	<div class="col-md">
		<div class="card">
			<div class="card-header">
				Tipe grafik
			</div>
			<div class="card-body">
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="bar">Column</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="horizontalBar">Bar</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="line">Line</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="area">Area</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="pie">Pie</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="doughnut">Doughnut</button>
				<button type="button" class="btn btn-primary btn-lg btn-chart" data-type="scatter">Scatter</button>
			</div>
		</div>
	</div>
</div>
<div class="row card-deck">
	<div class="card col px-0">
		<div class="card-header">
			<ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
	            <li class="nav-item">
	                <a class="nav-link active" href="#set-data" data-toggle="tab" role="tab" aria-controls="set-data" aria-selected="true">
	                    Data
	                </a>
	            </li>
	            <li class="nav-item">
	                <a class="nav-link" href="#set-title" data-toggle="tab" role="tab" aria-controls="set-title" aria-selected="false">
	                    Judul & Legend
	                </a>
	            </li>
	        </ul>
		</div>
		<div class="card-body">
			<form>
				<div class="tab-content" id="nav-tabContent">
					<div class="tab-pane fade show active" id="set-data" role="tabpanel" aria-labelledby="set-data-tab">
						<div class="common-chart">
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
									<select class="form-control form-control-sm frm-multiselect" id="selecty" name="selecty" multiple="multiple">
										@foreach($columns as $col)
											@if($col['type'] == 'numeric')
							            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
							            	@endif
							            @endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="scatter-chart mt-n2 mx-0" style="display: none">
							<input type="hidden" name="scatter-counter-value" id="scatter-counter-value" value="1">
							<div class="form-group row mb-0">
								<div class="col-auto px-0 scatter-col-counter">
									<div>Dataset&nbsp;#</div>
									<div class="scatter-row-counter-1">
										<label for="scatter-x" class="col-sm col-form-label form-control-sm text-center mt-1 scatter-counter">1</label>
									</div>
								</div>
								<div class="col-5 px-1 scatter-col-x">
									<div class="text-center">X</div>
									<div class="scatter-row-x-1">
										<select class="form-control form-control-sm scatter-x">
											@foreach($columns as $col)
												@if($col['type'] == 'numeric')
								            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
								            	@endif
								            @endforeach
										</select>
									</div>
								</div>
								<div class="col-5 px-1 scatter-col-y">
									<div class="text-center">Y</div>
									<div class="scatter-row-y-1">
										<select class="form-control form-control-sm scatter-y">
											@foreach($columns as $col)
												@if($col['type'] == 'numeric')
								            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
								            	@endif
								            @endforeach
										</select>
									</div>
								</div>
								<div class="col-auto px-1 scatter-col-button">
									<div>&nbsp;</div>
									<div class="scatter-row-button-1"> 
										<button type="button" class="close mt-1 border scatter-close" aria-label="Close" data-counter="1">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="set-title" role="tabpanel" aria-labelledby="set-title-tab">
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
					</div>
				</div>
			</form>
		</div>
		<div class="card-footer">
			<button type="button" class="btn btn-primary btn-sm btn-scatter-add" style="display: none">Tambah dataset</button>
			<button type="button" class="btn btn-primary btn-sm btn-chart-preview float-right">Pratinjau</button>
		</div>
	</div>
	<div class="card col px-0">
		<div class="card-body">
			<canvas id="chart-preview"></canvas>
		</div>
	</div>
</div>
<div class="row mt-3 mb-4">
	<div class="col-md">
		<div class="card">
			<div class="card-header text-right">
				<button type="button" class="btn btn-primary btn-lg" id="btn-publish">Selesai & tampilkan &raquo;</button>
			</div>
		</div>
	</div>
</div>

<div style="display: none">
	<form id="submit-form" action="{{ route('chart.setting.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
	    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
	    <input type="hidden" name="code" id="code" value="{{ $code }}">
	    <input type="text" name="options" id="options" value="">
	</form>
</div>

<div class="d-none">
	<div class="template-scatter-row-counter">
		<div class="scatter-row-counter-[VALSCATTERCOUNT]">
			<label for="scatter-x" class="col-sm col-form-label form-control-sm text-center mt-1 [VALSCATTERCOUNTERCLASS]">[VALSCATTERCOUNT]</label>
		</div>
	</div>
	<div class="template-scatter-row-x">
		<div class="scatter-row-x-[VALSCATTERCOUNT]">
			<select class="form-control form-control-sm [VALSCATTERCOMBOCLASS]">
				@foreach($columns as $col)
					@if($col['type'] == 'numeric')
	            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
	            	@endif
	            @endforeach
			</select>
		</div>
	</div>
	<div class="template-scatter-row-y">
		<div class="scatter-row-y-[VALSCATTERCOUNT]">
			<select class="form-control form-control-sm [VALSCATTERCOMBOCLASS]">
				@foreach($columns as $col)
					@if($col['type'] == 'numeric')
	            <option value="{{ $col['title'] }}">{{ $col['title'] }}</option>
	            	@endif
	            @endforeach
			</select>
		</div>
	</div>
	<div class="template-scatter-row-button">
		<div class="scatter-row-button-[VALSCATTERCOUNT]"> 
			<button type="button" class="close mt-1 border [VALSCATTERBUTTONCLASS]" aria-label="Close" data-counter="[VALSCATTERCOUNT]">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	</div>
</div>

@endsection


@section('js')

<script type="text/javascript">
    var progress_step = 3;
</script>
@include('chart.partials.progress_js')
@include('chart.partials.setting_js')

@endsection