@extends('Layouts.master')

@section('css')
    @include('Layouts.partials.css.jexcel')
    <style type="text/css">
        .jexcel { width: unset; }
        div[class^="step-progress"] {
            display: block;
            padding-top: 12px;
        }
    </style>
    @include('Layouts.partials.css.datatables')
@endsection


@section('content')
  
<!-- Page header -->
<!-- <p class="h1 mt-3">Check data</p> -->
@include('chart.partials.progress')

<div class="row mx-0">
    <div id="spreadsheet"></div>
</div>

<!-- <button type="button" class="btn btn-primary" id="prev-process">Sebelumnya</button>
<button type="button" class="btn btn-primary" id="next-process">Selanjutnya</button> -->

<div style="display: none">
    <form id="submit-form" action="{{ route('chart.check.post', ['code' => $code]) }}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        <input type="text" name="columns" id="columns" value="">
        <input type="text" name="data" id="data" value="">
    </form>
</div>

@endsection


@section('js')
<script type="text/javascript">
    var progress_step = 2;
</script>
@include('chart.partials.progress_js')

@include('Layouts.partials.js.jexcel')
@include('Layouts.partials.js.datatables')

<script type="text/javascript">
    var columns = eval('@json($columns)');
    var data = eval('@json($data)');

    var myTable = jexcel(document.getElementById('spreadsheet'), {
        // search:true,
        // pagination:10,
        columnSorting:false,
        data:data,
        columns: columns
    });
    // myTable.setData(data);

    $("#next-process").on("click", function() {
        var new_columns = myTable.getHeaders();
        var new_data = myTable.getData(false);

        $("#columns").val(new_columns);
        $("#data").val(JSON.stringify(new_data));
        $("#submit-form").submit();
    });

    $("#prev-process").on("click", function() {
        location.href = "{{ route('chart.upload.get', ['code' => $code]) }}";
    });

    $(document).ready( function () {
        $('.jexcel').DataTable({ "pageLength": 25, });
    } );
</script>

@endsection