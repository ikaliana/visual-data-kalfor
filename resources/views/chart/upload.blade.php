@extends('layouts.master')

@section('css')
    @include('Layouts.partials.css.jexcel')
@endsection

@section('content')
  
<!-- Page header -->
<p class="h1 mt-3">Pilih sumber data</p>

<!-- add tab header here -->

<div class="card mb-3">
    <div class="card-header">
        <ul class="nav nav-tabs card-header-tabs" id="nav-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#nav-upload" data-toggle="tab" role="tab" aria-controls="nav-upload" aria-selected="true">
                    Upload CSV / Excel file
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#nav-dataset" data-toggle="tab" role="tab" aria-controls="nav-dataset" aria-selected="true">
                    Pilih Dataset
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#nav-manual" data-toggle="tab" role="tab" aria-controls="nav-manual" aria-selected="true">
                    Manual (copy & paste)
                </a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-upload" role="tabpanel" aria-labelledby="nav-home-tab">
                <div class="form-group">
                    <label for="file">Upload dari file Excel/CSV (.xlsx,.xls,.csv)</label>
                    <input type="file" class="form-control-file" id="file" name="file">
                </div>
            </div>
            <div class="tab-pane fade" id="nav-dataset" role="tabpanel" aria-labelledby="nav-profile-tab">
                <div class="form-group">
                    <!-- <label for="exampleFormControlSelect1" class="col-md-2 col-form-label">Dataset</label> -->
                    <div class="col-md-6 row">
                        <select class="form-control" id="datasource">
                            @foreach($datasource as $ds)
                            <option value="{{ $ds->id }}">{{ $ds->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-manual" role="tabpanel" aria-labelledby="nav-contact-tab">
                <div id="spreadsheet">
                    <div class="custom-control custom-switch mb-2">
                        <input type="checkbox" class="custom-control-input" id="chkHeader" checked="checked">
                        <label class="custom-control-label" for="chkHeader">Baris pertama adalah Header</label>
                    </div>
                </div>
                <p><em>Pastikan kolom atau baris yang tidak ada datanya/tidak terpakai dihapus sebelum lanjut ke langkah berikutnya</em></p>
            </div>
        </div>
    </div>
</div>
<button type="button" class="btn btn-primary" id="next-process">Selanjutnya</button>

<div style="display: none">
    <form id="submit-form" action="{{ route('chart.upload.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="hidden" name="code" id="code" value="{{ $code }}">
        <input type="hidden" name="tab" id="tab" value="">
        <input type="hidden" name="ds_id" id="ds_id" value="">
        <input type="hidden" name="header" id="header" value="">
        <input type="text" name="data" id="data" value="">
    </form>
</div>


@endsection



@section('js')
@include('Layouts.partials.js.jexcel')
<script type="text/javascript">
    var active_tab = "nav-upload";
    $("#tab").val(active_tab);

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var tab = e.target;
        active_tab = $(tab).attr("aria-controls");
        $("#tab").val(active_tab);
    });

    $("#next-process").on("click", function() {
        var action = "";

        if(active_tab == "nav-upload") {
            $("#file").clone().appendTo("#submit-form");
        }
        if(active_tab == "nav-dataset") {
            $("#ds_id").val($("#datasource").val());
        }
        if(active_tab == "nav-manual") {
            var isFirstRowHeader = $("#chkHeader").is(':checked') ? "1" : "0";
            $("#data").val(JSON.stringify(mySheet.getData()));
            $("#header").val(isFirstRowHeader);
        }

        $("#submit-form").submit();
    });

    var mySheet = jexcel(document.getElementById('spreadsheet'), {
        minDimensions:[5,5],
        tableOverflow: true,
        tableWidth: "800px",
        tableHeight: "500px",
    });
</script>

@endsection