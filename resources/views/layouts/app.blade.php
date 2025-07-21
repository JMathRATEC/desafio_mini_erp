<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Mini ERP')</title>

  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" 
    rel="stylesheet"
    integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr"
    crossorigin="anonymous"
  >
  <link 
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" 
    rel="stylesheet"
  >

  <style>
    body, html { height: 100%; margin: 0; }
    .sidebar {
      width: 240px;
      background: #0d6efd;
      color: white;
      flex-shrink: 0;
    }
    .sidebar .nav-link {
      color: #e9ecef;
    }
    .sidebar .nav-link.active {
      background: rgba(255,255,255,0.2);
    }
    main {
      overflow-y: auto;
      flex-grow: 1;
      padding: 1.5rem;
    }
  </style>
</head>
<body class="d-flex flex-column">

  <nav class="navbar navbar-light bg-light shadow-sm">
    <div class="container-fluid">
      <button class="btn btn-outline-primary d-lg-none" 
              data-bs-toggle="offcanvas" 
              data-bs-target="#sidebarOffcanvas">
        <i class="bi bi-list"></i>
      </button>
      <span class="navbar-brand mb-0 h1">Mini ERP</span>
      <button class="btn btn-outline-primary position-relative" 
              data-bs-toggle="offcanvas" 
              data-bs-target="#cartOffcanvas">
        <i class="bi bi-cart"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
          {{ count(session('cart', [])) }}
        </span>
      </button>
    </div>
  </nav>

  <div class="d-flex flex-grow-1">

    <div class="sidebar d-none d-lg-flex flex-column p-3">
      <h5 class="text-center mb-4">Menu</h5>
      <ul class="nav nav-pills flex-column">
        <li class="nav-item mb-2">
          <a href="{{ url('/') }}" 
             class="nav-link @if(request()->is('/')) active @endif">
            <i class="bi bi-box-seam me-2"></i>Produtos
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="#" 
             class="nav-link" 
             data-bs-toggle="offcanvas" 
             data-bs-target="#cartOffcanvas">
            <i class="bi bi-cart me-2"></i>Carrinho
          </a>
        </li>
        <li class="nav-item mb-2">
          <a href="{{ route('cupons.index') }}" 
             class="nav-link @if(request()->is('cupons*')) active @endif">
            <i class="bi bi-ticket-perforated me-2"></i>Cupons
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ route('pedidos.index') }}" 
             class="nav-link @if(request()->is('pedidos*')) active @endif">
            <i class="bi bi-receipt me-2"></i>Pedidos
          </a>
        </li>
      </ul>
    </div>

    <main>
      @yield('content')
    </main>
  </div>

  @include('carrinho')

  <div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas">
    <div class="offcanvas-header bg-primary text-white">
      <h5 class="offcanvas-title">Menu</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body p-0 bg-primary">
      <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ url('/') }}" class="nav-link text-white">Produtos</a></li>
        <li class="nav-item"><a href="#" class="nav-link text-white" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">Carrinho</a></li>
        <li class="nav-item"><a href="{{ route('cupons.index') }}" class="nav-link text-white">Cupons</a></li>
        <li class="nav-item"><a href="{{ route('pedidos.index') }}" class="nav-link text-white">Pedidos</a></li>
      </ul>
    </div>
  </div>

  <script 
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q"
    crossorigin="anonymous">
  </script>
</body>
</html>