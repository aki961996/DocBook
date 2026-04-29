<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
  <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Dashboard') — DocBook Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  <style>
    :root { --tblr-primary: #0f6e56; --tblr-primary-rgb: 15,110,86; }
    .navbar-brand-image { width: 32px; height: 32px; }
    .sidebar-logo-text { font-weight: 700; font-size: 1.1rem; color: #fff; letter-spacing: .5px; }
    .nav-link.active { background: rgba(255,255,255,.12) !important; border-radius: 6px; }
    .badge-status-pending   { background:#ffc107;color:#000; }
    .badge-status-confirmed { background:#0f6e56;color:#fff; }
    .badge-status-completed { background:#198754;color:#fff; }
    .badge-status-cancelled { background:#dc3545;color:#fff; }
  </style>
  @stack('styles')
</head>
<body class="antialiased">
<div class="wrapper">

  {{-- ── Sidebar ───────────────────────────────────────── --}}
  <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark"
         style="background:#0f6e56;">
    <div class="container-fluid">

      {{-- Mobile toggle --}}
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
              data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
      </button>

      {{-- Brand --}}
      <a href="{{ route('admin.dashboard') }}" class="navbar-brand d-flex align-items-center gap-2">
        <span style="font-size:1.6rem;">🏥</span>
        <span class="sidebar-logo-text">DocBook</span>
      </a>

      {{-- User avatar (mobile) --}}
      <div class="navbar-nav flex-row d-lg-none">
        <span class="text-white small">{{ auth('admin')->user()->name }}</span>
      </div>

      {{-- Nav links --}}
      <div class="collapse navbar-collapse" id="sidebarMenu">
        <ul class="navbar-nav pt-lg-3">

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
               href="{{ route('admin.dashboard') }}">
              <span class="nav-link-icon"><i class="ti ti-layout-dashboard"></i></span>
              <span class="nav-link-title">Dashboard</span>
            </a>
          </li>

          @if(auth('admin')->user()->isSuperAdmin())
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.hospitals.*') ? 'active' : '' }}"
               href="{{ route('admin.hospitals.index') }}">
              <span class="nav-link-icon"><i class="ti ti-building-hospital"></i></span>
              <span class="nav-link-title">Hospitals</span>
            </a>
          </li>
          @endif

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.departments.*') ? 'active' : '' }}"
               href="{{ route('admin.departments.index') }}">
              <span class="nav-link-icon"><i class="ti ti-sitemap"></i></span>
              <span class="nav-link-title">Departments</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}"
               href="{{ route('admin.doctors.index') }}">
              <span class="nav-link-icon"><i class="ti ti-stethoscope"></i></span>
              <span class="nav-link-title">Doctors</span>
            </a>
          </li>

          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}"
               href="{{ route('admin.bookings.index') }}">
              <span class="nav-link-icon"><i class="ti ti-calendar-check"></i></span>
              <span class="nav-link-title">Bookings</span>
            </a>
          </li>

        </ul>

        {{-- Bottom: logout --}}
        <div class="mt-auto pb-3">
          <div class="px-3 py-2 border-top border-white border-opacity-25">
            <div class="d-flex align-items-center gap-2 mb-2">
              <span class="avatar avatar-sm" style="background:#1d9e75;">
                {{ strtoupper(substr(auth('admin')->user()->name,0,1)) }}
              </span>
              <div class="text-white">
                <div class="fw-semibold small">{{ auth('admin')->user()->name }}</div>
                <div class="text-white-50" style="font-size:.7rem;">
                  {{ ucfirst(str_replace('_',' ', auth('admin')->user()->role)) }}
                </div>
              </div>
            </div>
            <form method="POST" action="{{ route('admin.logout') }}">
              @csrf
              <button type="submit" class="btn btn-sm w-100"
                      style="background:rgba(255,255,255,.15);color:#fff;border:none;">
                <i class="ti ti-logout me-1"></i> Logout
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </aside>

  {{-- ── Page wrapper ─────────────────────────────────── --}}
  <div class="page-wrapper">

    {{-- Top navbar --}}
    <div class="navbar-expand-md">
      <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar py-0">
          <div class="container-xl">
            <div class="me-auto">
              <h2 class="page-title mb-0">@yield('page-title', 'Dashboard')</h2>
            </div>
            <div class="d-flex align-items-center gap-2">
              @yield('page-actions')
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Flash messages --}}
    <div class="container-xl mt-3">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="ti ti-circle-check me-2"></i>{{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="ti ti-alert-circle me-2"></i>{{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
          <i class="ti ti-alert-circle me-2"></i>
          <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
    </div>

    {{-- Main content --}}
    <div class="page-body">
      <div class="container-xl">
        @yield('content')
      </div>
    </div>

    {{-- Footer --}}
    <footer class="footer footer-transparent d-print-none">
      <div class="container-xl text-center text-muted small py-3">
        DocBook Admin &copy; {{ date('Y') }}
      </div>
    </footer>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
@stack('scripts')
</body>
</html>
