<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Visualisasi Data</title>

    <!-- Bootstrap Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Custom Styles -->
    @yield('css')
  </head>

  <body>
    @include('Layouts.partials.navs')

    <div class="container">
      @yield('content')
    </div>
  </body>

  <script src="/js/app.js"></script>
  @yield('js')

</html>
