<nav class="navbar navbar-expand-sm navbar-dark bg-info" style="min-height: 78px;">
  <div class="container-xl">
    @if(!Request::is('/'))
    <a class="navbar-brand px-4 mt-2" href="{{ route('home') }}">
      <img src="{{ asset('icons/logo.png') }}" width="50" height="50" class="d-inline-block mr-2 mt-n3" alt="" loading="lazy">
      <h2 style="display: unset">Visualisasi Data</h2>
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
          <a class="nav-link" href="{{ route('map.create') }}"><i class="fas fa-map-marked-alt mr-1"></i> Peta</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#"><i class="fas fa-table mr-1"></i> Tabel</a>
        </li>
      @endif
      </ul>

      @if($loginfo['islogin'])
      <div class="btn-group">
        <button type="button" class="btn btn-light">
          <i class="fas fa-user-circle mr-2"></i> {{ $loginfo['name'] }}
        </button>
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split border-left" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <div class="dropdown-menu">
          <!-- <a class="dropdown-item" href="#">Action</a>
          <a class="dropdown-item" href="#">Another action</a>
          <a class="dropdown-item" href="#">Something else here</a>
          <div class="dropdown-divider"></div> -->
          <a class="dropdown-item px-3 d-flex justify-content-between align-items-center" href="{{ route('logout') }}">
            Logout <i class="fas fa-sign-out-alt"></i>
          </a>
        </div>
      </div>
      <!-- <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="now-ui-icons users_circle-08"></i>
            <p>{{ $loginfo['name'] }}</p>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
            <a class="dropdown-item" href="{{ route('logout') }}">Logout</a>
          </div>
        </li>
      </ul> -->
      @else
      <a class="btn btn-light" href="{{ route('login') }}">Login <i class="fas fa-sign-in-alt mr-1"></i></a>
      @endif

    </div>
  </div>
</nav>