<nav class="navbar navbar-expand-sm navbar-dark bg-info" style="min-height: 78px;">
  <div class="container-xl">
    @if(!Request::is('/'))
    <a class="navbar-brand px-4 border rounded" href="{{ route('home') }}">
      <img src="{{ asset('icons/logo.png') }}" width="40" height="40" class="d-inline-block mr-2 my-1" alt="" loading="lazy">
      Visualisasi Data
    </a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-links" aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav-links">
      <ul class="navbar-nav mr-auto ml-auto">
      @if(!Request::is('/'))
        <li class="nav-item active">
          <a class="nav-link" href="{{ route('chart.create') }}"><i class="far fa-chart-bar mr-1"></i> Grafik</a>
        </li>
        <li class="nav-item active mx-3">
          <a class="nav-link" href="#"><i class="fas fa-map-marked-alt mr-1"></i> Peta</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fas fa-table mr-1"></i> Tabel</a>
        </li>
      @endif
      </ul>
      <a class="btn btn-light" href="#">Login <i class="fas fa-sign-in-alt mr-1"></i></a>
    </div>
  </div>
</nav>