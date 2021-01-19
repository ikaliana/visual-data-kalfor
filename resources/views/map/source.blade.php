@extends('Layouts.master')

@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('css/styleUploader.css') }}">
@endsection


@section('content')
@include('map.partials.progress')  

<div>
	<button type="button" class="btn btn-link" data-toggle="modal" data-target="#modalUpl1" id="btnUpload2">
		<i class="fas fa-file-upload"></i> Upload Dataset
	</button>
</div>

<table class="table table-striped">
	<thead>
		<tr>
			<th width="5%">Terpilih</th>
			<th width="25%">Nama</th>
			<th width="70%">Deskripsi</th>
		</tr>
	</thead>
	<tbody>
		@foreach($listShapefile as $ls)
            <tr>
            	<td> 
					<input type='checkbox' class="chk-pilih" name='terpilih[]' value="{{ $ls['nama'] }}"/>
				</td>
            	<td>{{ $ls['nama'] }}</td>
            	<td>{{ $ls['deskripsi'] }}</td>
            </tr>
        @endforeach
	</tbody>
</table>

<div style="display: none">
    <form id="submit-form" action="{{ route('map.source.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="hidden" name="code" id="code" value="{{ $code }}">
        <input type="hidden" name="data" id="data" value="">
    </form>
</div>

<div class="modal fade" id="modalUpl1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Upload Shapefile (<u>Keterangan</u>)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <ul class="list-group">
        	<li class="list-group-item list-group-item-warning" id="pesan1" style="display: none;">warning.</li>
		</ul>
        <div class="progress">
		  	<div class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="progress1">0%</div>
		</div>
        <form method="post" action="{{ route('map.source.upload') }}" enctype="multipart/form-data" id="form1">
        	@csrf
        	<div class="form-group">
			    <label for="namaFile">Nama File</label>
			    <input type="text" class="form-control" name="namaFile" id="namaFile">
			</div>
        	<div class="form-group">
			    <label for="deskripsi">Deskripsi File</label>
			    <textarea class="form-control" name="deskripsi"></textarea>
			</div>
        	<div class="form-group files color">
        		<input type="file" class="form-control" name="fileShp" accept=".zip,.rar">
        	</div>
        	<input type="submit" class="btn btn-primary btn-lg btn-block" id="btnSubmit1" value="Upload">
        </form>
    </div>
      	<div class="modal-footer">
        	
      	</div>
    </div>
  </div>
</div>
@endsection


@section('js')

<script type="text/javascript">
    var progress_step = 1;
</script>
@include('map.partials.progress_js')

<script type="text/javascript">
	var btnUploadClicked = false;

	$("#next-process").on("click", function() {
		var data = [];

		$.each($(".chk-pilih"), function(i,chk) {
			if($(chk).is(':checked')) data.push($(chk).val());
		});

		if(data.length != 0)
		{
	        $("#data").val(JSON.stringify(data));
	        $("#submit-form").submit();
	        return;
		}

		alert("Silahkan pilih minimal satu Shapefile tersimpan!");
    });

    $("#modalUpl1").on('hidden.bs.modal', function(){
	    var percent = $('#progress1');
		percent.css('width', '0%').attr('aria-valuenow', 0);
		percent.html('0%');
		$("#pesan1").hide();

		if(btnUploadClicked) location.reload();
		btnUploadClicked = false;
	});

	$('#btnSubmit1').click(function(event) {
		btnUploadClicked = true;
		event.preventDefault();
    	var formData = new FormData($('#form1')[0]);
	    var bar = $('#progress1').css("width");
	    var percent = $('#progress1');

	    percent.css('width', '0%').attr('aria-valuenow', 0);
	    percent.html('0%');			    

	    $.ajax({
			xhr: function() {
			    var xhr = new window.XMLHttpRequest();

			    xhr.upload.addEventListener("progress", function(evt) {
			      if (evt.lengthComputable) {
			        var percentComplete = evt.loaded / evt.total;
			        percentComplete = parseInt(percentComplete * 100);
			        percent.css('width', percentComplete+'%').attr('aria-valuenow', percentComplete);
			        percent.html(percentComplete+'%');

			        if (percentComplete === 100) {
			        	percent.css('width', '100%').attr('aria-valuenow', percentComplete);
			        	percent.html('100%');
			        }

			      }
			    }, false);

			    return xhr;
			},
			url: "{{ route('map.source.upload') }}",
	        type: 'POST',
	        data: formData,
	        processData: false,
        	contentType: false,
	        success: function(msg) {
	            $('#pesan1').html(msg);
	            $("#pesan1").show();
	        },
	        error: function(XMLHttpRequest, textStatus, errorThrown) {
	        	$('#pesan1').html(errorThrown);
	        	$("#pesan1").show();
	        }      
		});

	});

</script>

@endsection