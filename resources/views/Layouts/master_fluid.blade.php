<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visualisasi Data</title>

    <!-- Bootstrap Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Custom Styles -->
    @yield('css')
  </head>

  <body>
    @include('Layouts.partials.navs')

    <div class="container-fluid">
      @yield('content')
    </div>
  </body>

  <!-- Jquery + bootstrap -->
  <script src="{{ asset('js/app.js') }}"></script>
  
  @yield('js')

</html>
