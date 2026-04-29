<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Admin Login — DocBook</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/css/tabler.min.css">
  <style>
    body { background: #f0faf6; }
    .login-card { border-top: 4px solid #0f6e56; }
    .btn-login { background:#0f6e56; color:#fff; border:none; }
    .btn-login:hover { background:#085041; color:#fff; }
    .brand-icon { font-size:2.5rem; }
  </style>
</head>
<body class="d-flex align-items-center" style="min-height:100vh;">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-5 col-lg-4">

      {{-- Brand --}}
      <div class="text-center mb-4">
        <span class="brand-icon">🏥</span>
        <h1 class="fw-bold mt-2" style="color:#0f6e56;">DocBook</h1>
        <p class="text-muted">Admin Panel</p>
      </div>

      <div class="card shadow-sm login-card">
        <div class="card-body p-4">
          <h3 class="card-title mb-1">Sign in</h3>
          <p class="text-muted small mb-4">Enter your admin credentials</p>

          <form method="POST" action="{{ route('admin.login') }}">
            @csrf

            <div class="mb-3">
              <label class="form-label">Email address</label>
              <input type="email" name="email"
                     class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email') }}" placeholder="admin@example.com" autofocus>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">Password</label>
              <div class="input-group">
                <input type="password" name="password" id="passwordInput"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••">
                <button type="button" class="btn btn-outline-secondary"
                        onclick="togglePassword()">
                  <i class="ti ti-eye" id="eyeIcon"></i>
                </button>
              </div>
              @error('password')
                <div class="text-danger small mt-1">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label class="form-check">
                <input class="form-check-input" type="checkbox" name="remember">
                <span class="form-check-label">Remember me</span>
              </label>
            </div>

            <button type="submit" class="btn btn-login w-100">
              <i class="ti ti-login me-1"></i> Sign in
            </button>
          </form>
        </div>
      </div>

      <p class="text-center text-muted small mt-3">
        DocBook &copy; {{ date('Y') }}
      </p>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta17/dist/js/tabler.min.js"></script>
<script>
function togglePassword() {
  const inp = document.getElementById('passwordInput');
  const icon = document.getElementById('eyeIcon');
  if (inp.type === 'password') {
    inp.type = 'text';
    icon.className = 'ti ti-eye-off';
  } else {
    inp.type = 'password';
    icon.className = 'ti ti-eye';
  }
}
</script>
</body>
</html>
