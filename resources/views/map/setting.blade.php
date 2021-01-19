@extends('Layouts.master')

@section('content')
@include('map.partials.progress')  

<div class="row row-cols-md-2">
	@foreach ($dataset as $data)
	<div class="col mb-3">
		<div class="card mb-3">
			<div class="card-header">Nama dataset: {{ $data["folder"] }}</div>
			<div class="card-body pb-2 pt-3">
				<form>
					<input type="hidden" name="folder-{{ $loop->iteration }}" id="folder-{{ $loop->iteration }}" class="folder" value='{{ $data["folder"] }}'>
					<div class="form-row">
						<div class="form-group col-md-6">
						    <label for="kolom1">Kolom yang ingin dianalisis</label>
						    <select class="form-control kolom" id="kolom-{{ $loop->iteration }}" name="kolom-{{ $loop->iteration }}">
				    		@foreach($data['num'] as $ls1)
				    			<option>{{ $ls1 }}</option>
	                        @endforeach
						    </select>
						</div>					
						<div class="form-group col-md-6">
							<label for="group1">Kolom yang ingin dijadikan grup</label>
					    	<select class="form-control group" id="group-{{ $loop->iteration }}" name="group-{{ $loop->iteration }}">
				    		@foreach($data['non'] as $ls1)
				    			<option>{{ $ls1 }}</option>
	                        @endforeach
						    </select>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	@endforeach
</div>

<div style="display: none">
    <form id="submit-form" action="{{ route('map.setting.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="hidden" name="code" id="code" value="{{ $code }}">
        <input type="hidden" name="data" id="data" value="">
    </form>
</div>

@endsection

@section('js')

<script type="text/javascript">
    var progress_step = 2;
</script>

@include('map.partials.progress_js')

<script type="text/javascript">
	$("#prev-process").on("click", function() {
        location.href = "{{ route('map.source.list', ['code' => $code]) }}";
    });

	$("#analisis").on("click", function() {
        var folders = [];
		$.each($(".folder"), function(i,opt) { folders.push($(opt).val()); });

        var kolom = [];
		$.each($(".kolom"), function(i,opt) { kolom.push($(opt).val()); });

        var group = [];
		$.each($(".group"), function(i,opt) { group.push($(opt).val()); });

		data= [];
		$.each(folders, function(i,f) {
			data.push( { 'folder': f, 'kolom': kolom[i], 'group': group[i] } );
		});

		if(data.length != 0)
		{
	        $("#data").val(JSON.stringify(data));
	        $("#submit-form").submit();
	        return;
		}

		alert("Silahkan pilih kolom dan group yang akan diproses!");
    });
</script>

@endsection