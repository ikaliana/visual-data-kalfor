<nav class="navbar navbar-expand-sm navbar-dark bg-info">
  <div class="container-xl">
    @if(!Request::is('/'))
    <a class="navbar-brand px-4 border rounded" href="#">Visualisasi Data</a>
    @endif
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#nav-links" aria-controls="nav-links" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="nav-links">
      <ul class="navbar-nav mr-auto ml-auto">
        <li class="nav-item active">
          <a class="nav-link" href="#">Chart</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">Map</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="#">Table</a>
        </li>
      </ul>
        <a class="btn btn-light" href="#">Login</a>
    </div>
  </div>
</nav>