@extends('layouts.master')

@section('content')
  <div class="px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
    <h1 class="display-4">Visualisasi Data</h1>
    <p class="lead">Pilih jenis visualisasi data yang akan dibuat:</p>
  </div>
  <div class="container">
    <div class="card-deck mb-3 text-center">
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="my-0 font-weight-normal">Grafik</h4>
        </div>
        <img src="{{ asset('icons/chart.png') }}" class="rounded mx-auto d-block mt-3" alt="Chart" >
        <div class="card-body">
          <a type="button" class="btn btn-lg btn-primary" href="{{ route('chart.create') }}">Mulai</a>
        </div>
      </div>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="my-0 font-weight-normal">Peta</h4>
        </div>
        <img src="{{ asset('icons/map.png') }}" class="rounded mx-auto d-block mt-3" alt="Chart" >
        <div class="card-body">
          <a type="button" class="btn btn-lg btn-primary" href="#">Mulai</a>
        </div>
      </div>
      <div class="card mb-4 shadow-sm">
        <div class="card-header bg-info text-white">
          <h4 class="my-0 font-weight-normal">Tabel</h4>
        </div>
        <img src="{{ asset('icons/table.png') }}" class="rounded mx-auto d-block mt-3" alt="Chart" >
        <div class="card-body">
          <a type="button" class="btn btn-lg btn-primary" href="#">Mulai</a>
        </div>
      </div>
    </div>            
  </div>
@endsection