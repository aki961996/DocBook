<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'DocBook') — Find & Book Doctors</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
      --navy:   #0d1b3e;
      --navy2:  #132044;
      --teal:   #0f9b82;
      --teal2:  #0d8470;
      --light:  #f0faf8;
    }
    * { box-sizing: border-box; }
    body { background: #f5f7fb; font-family: 'Segoe UI', sans-serif; }

    /* ── Navbar ── */
    .site-nav {
      background: var(--navy);
      padding: 0 1rem;
      position: sticky; top: 0; z-index: 100;
      box-shadow: 0 2px 12px rgba(0,0,0,.3);
    }
    .site-nav .navbar-brand {
      color: #fff !important;
      font-weight: 700; font-size: 1.3rem; letter-spacing: .5px;
    }
    .site-nav .nav-link { color: rgba(255,255,255,.8) !important; font-size:.95rem; }
    .site-nav .nav-link:hover { color: #fff !important; }
    .btn-teal {
      background: var(--teal); color: #fff !important;
      border: none; border-radius: 50px;
      padding: .45rem 1.3rem; font-weight: 600; font-size: .9rem;
      transition: background .2s;
    }
    .btn-teal:hover { background: var(--teal2); color: #fff; }
    .btn-teal-outline {
      background: transparent; color: var(--teal) !important;
      border: 2px solid var(--teal); border-radius: 50px;
      padding: .4rem 1.2rem; font-weight: 600; font-size: .9rem;
      transition: all .2s;
    }
    .btn-teal-outline:hover { background: var(--teal); color: #fff !important; }

    /* ── Cards ── */
    .card-hover {
      transition: transform .2s, box-shadow .2s;
      border: none; border-radius: 14px;
    }
    .card-hover:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.12); }

    /* ── Badge ── */
    .dept-badge {
      background: var(--light); color: var(--teal);
      border: 1px solid rgba(15,155,130,.25);
      border-radius: 20px; padding: .25rem .8rem;
      font-size: .78rem; font-weight: 600;
    }

    /* ── Footer ── */
    footer { background: var(--navy); color: rgba(255,255,255,.7); }
    footer a { color: rgba(255,255,255,.6); text-decoration: none; }
    footer a:hover { color: #fff; }

    /* ── Mobile tweaks ── */
    @media(max-width:576px){
      .site-nav .navbar-brand { font-size: 1.1rem; }
    }
  </style>
  @stack('styles')
</head>
<body>

{{-- ── Navbar ────────────────────────────────────────── --}}
<nav class="site-nav navbar navbar-expand-lg">
  <div class="container">
    <a class="navbar-brand" href="{{ route('home') }}">
      🏥 DocBook
    </a>
    <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#mainNav"
            style="color:#fff;">
      <i class="bi bi-list" style="font-size:1.5rem;"></i>
    </button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto gap-1 mt-2 mt-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'fw-semibold text-white' : '' }}"
             href="{{ route('home') }}">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('hospitals.*') ? 'fw-semibold text-white' : '' }}"
             href="{{ route('hospitals.index') }}">Hospitals</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('doctors.*') ? 'fw-semibold text-white' : '' }}"
             href="{{ route('doctors.index') }}">Doctors</a>
        </li>
      </ul>
      <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0 pb-2 pb-lg-0">
        @auth('web')
          <a href="{{ route('bookings.index') }}" class="nav-link text-white">
            <i class="bi bi-calendar-check me-1"></i>My Bookings
          </a>
          <form method="POST" action="{{ route('logout') }}" class="d-inline">
            @csrf
            <button class="btn-teal-outline">Logout</button>
          </form>
        @else
          <a href="{{ route('login') }}" class="btn-teal">
            <i class="bi bi-whatsapp me-1"></i> Login
          </a>
        @endauth
      </div>
    </div>
  </div>
</nav>

{{-- ── Flash messages ───────────────────────────────── --}}
@if(session('success') || session('error'))
<div class="container mt-3">
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0 rounded-3">
      <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3">
      <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  @endif
</div>
@endif

{{-- ── Page content ─────────────────────────────────── --}}
@yield('content')

{{-- ── Footer ──────────────────────────────────────── --}}
<footer class="mt-5 py-4">
  <div class="container">
    <div class="row gy-3">
      <div class="col-md-4">
        <div class="fw-bold fs-5 text-white mb-2">🏥 DocBook</div>
        <p class="small mb-0">Book doctor appointments easily via WhatsApp.</p>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold text-white mb-2">Quick Links</div>
        <div class="d-flex flex-column gap-1 small">
          <a href="{{ route('home') }}">Home</a>
          <a href="{{ route('hospitals.index') }}">Hospitals</a>
          <a href="{{ route('doctors.index') }}">Doctors</a>
        </div>
      </div>
      <div class="col-md-4">
        <div class="fw-semibold text-white mb-2">Contact</div>
        <div class="small">
          <div><i class="bi bi-whatsapp me-2"></i>+91 98765 43210</div>
          <div><i class="bi bi-envelope me-2"></i>help@docbook.in</div>
        </div>
      </div>
    </div>
    <hr style="border-color:rgba(255,255,255,.15);" class="my-3">
    <p class="text-center small mb-0">DocBook &copy; {{ date('Y') }}</p>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
