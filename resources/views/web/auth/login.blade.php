<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Sign Up — DocBook</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root { --navy:#0d1b3e; --teal:#0f9b82; --teal2:#0d8470; }
    * { box-sizing: border-box; }

    body {
      min-height: 100vh;
      background: var(--navy);
      font-family: 'Segoe UI', sans-serif;
      display: flex; flex-direction: column;
    }

    /* ── Top section (navy) ── */
    .top-section {
      background: var(--navy);
      flex: 0 0 auto;
      display: flex; flex-direction: column;
      align-items: center;
      padding: 2.5rem 1rem 2rem;
    }
    .top-section h2 {
      color: #fff; font-weight: 700; font-size: 1.5rem;
      margin-bottom: 1.5rem;
    }

    /* Phone illustration (CSS) */
    .phone-illus {
      width: 90px; height: 90px;
      background: rgba(255,255,255,.15);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 1.5rem;
    }
    .phone-illus .inner {
      width: 46px; height: 70px;
      background: #f4a05a;
      border-radius: 8px;
      position: relative;
      display: flex; flex-direction: column;
      align-items: center; justify-content: center;
      gap: 5px;
    }
    .phone-illus .screen {
      width: 30px; height: 28px;
      background: #fff;
      border-radius: 3px;
    }
    .phone-illus .btn-bar {
      width: 22px; height: 5px;
      background: rgba(255,255,255,.6);
      border-radius: 3px;
    }

    /* OTP illustration */
    .otp-illus {
      width: 90px; height: 90px;
      background: rgba(255,255,255,.12);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 1.5rem;
    }

    /* ── Bottom card (white) ── */
    .bottom-card {
      background: #fff;
      border-radius: 28px 28px 0 0;
      flex: 1;
      padding: 2rem 1.5rem 2.5rem;
    }
    @media(min-width:480px){
      body { justify-content: center; align-items: center; }
      .auth-wrapper {
        width: 100%; max-width: 420px;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 24px 64px rgba(0,0,0,.4);
      }
      .bottom-card { border-radius: 0; flex: none; }
      .top-section { border-radius: 24px 24px 0 0; }
    }

    .bottom-card p.subtitle {
      color: #555; font-size: .93rem; margin-bottom: 1.4rem;
    }

    /* Country selector */
    .phone-input-group {
      display: flex; gap: 8px; margin-bottom: 1rem;
    }
    .country-select {
      display: flex; align-items: center; gap: 6px;
      border: 1.5px solid #dde; border-radius: 10px;
      padding: .55rem .8rem; cursor: pointer;
      background: #f8f9ff; white-space: nowrap;
      font-size: .92rem; font-weight: 600; color: #333;
      min-width: fit-content;
    }
    .flag { font-size: 1.2rem; }

    .phone-field {
      flex: 1;
      border: 1.5px solid #dde; border-radius: 10px;
      padding: .55rem 1rem; font-size: .95rem;
      outline: none; transition: border .2s;
    }
    .phone-field:focus { border-color: var(--teal); }

    /* OTP boxes */
    .otp-boxes {
      display: flex; gap: 10px; justify-content: center;
      margin: 1rem 0 .5rem;
    }
    .otp-box {
      width: 52px; height: 54px;
      border: 1.5px solid #dde;
      border-radius: 12px;
      font-size: 1.4rem; font-weight: 700;
      text-align: center;
      outline: none; transition: border .2s;
      color: #222;
    }
    .otp-box:focus { border-color: var(--teal); box-shadow: 0 0 0 3px rgba(15,155,130,.15); }

    .resend-text {
      text-align: center; color: #888; font-size: .85rem;
      margin-bottom: 1.2rem;
    }
    .resend-text span { color: var(--teal); font-weight: 600; cursor: pointer; }

    /* Main button */
    .btn-main {
      width: 100%;
      background: var(--teal); color: #fff;
      border: none; border-radius: 50px;
      padding: .85rem; font-size: 1rem; font-weight: 700;
      letter-spacing: .5px;
      transition: background .2s, transform .1s;
    }
    .btn-main:hover { background: var(--teal2); transform: translateY(-1px); }
    .btn-main:active { transform: translateY(0); }
    .btn-main:disabled { background: #aaa; cursor: not-allowed; }

    /* Error */
    .err-msg { color: #dc3545; font-size: .84rem; margin-top: .4rem; }
  </style>
</head>
<body>

<div class="auth-wrapper" id="signupStep">

  {{-- ═══════════════════════════════
       STEP 1 — Enter Phone Number
  ═══════════════════════════════ --}}
  <div id="step1">
    <div class="top-section">
      <h2>Sign Up</h2>
      <div class="phone-illus">
        <div class="inner">
          <div class="screen"></div>
          <div class="btn-bar"></div>
        </div>
      </div>
    </div>

    <div class="bottom-card">
      <p class="subtitle">
        We need to send OTP to authenticate<br>
        your mobile number and email address.
      </p>

      <form id="phoneForm">
        @csrf
        <div class="phone-input-group">
          <div class="country-select">
            <span class="flag">🇮🇳</span>
            <span>IN +91</span>
            <i class="bi bi-chevron-down" style="font-size:.7rem;"></i>
          </div>
          <input type="tel" id="phoneInput" class="phone-field"
                 placeholder="Enter Your WhatsApp Number"
                 maxlength="10" inputmode="numeric"
                 pattern="[6-9][0-9]{9}">
        </div>
        <div id="phoneError" class="err-msg d-none"></div>

        <button type="submit" class="btn-main mt-2" id="getOtpBtn">
          GET OTP
        </button>
      </form>

      <p class="text-center mt-3 mb-0" style="font-size:.85rem;color:#888;">
        Already have an account?
        <a href="{{ route('login') }}" style="color:var(--teal);font-weight:600;">Login</a>
      </p>
    </div>
  </div>

  {{-- ═══════════════════════════════
       STEP 2 — OTP Verification
  ═══════════════════════════════ --}}
  <div id="step2" class="d-none">
    <div class="top-section">
      <h2>OTP verification</h2>
      <div class="otp-illus">
        <i class="bi bi-shield-lock-fill" style="font-size:2.2rem;color:#fff;"></i>
      </div>
    </div>

    <div class="bottom-card">
      <p class="subtitle">
        We need to send OTP to authenticate<br>
        your mobile number and email address.
      </p>

      <form id="otpForm" method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <input type="hidden" name="phone" id="hiddenPhone">

        <div class="otp-boxes">
          <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="0">
          <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="1">
          <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="2">
          <input type="text" class="otp-box" maxlength="1" inputmode="numeric" data-index="3">
        </div>
        <input type="hidden" name="otp" id="otpValue">

        <div class="resend-text" id="resendArea">
          Resend OTP in : <span id="timerCount">30</span> seconds
        </div>
        <div id="resendBtn" class="resend-text d-none">
          Didn't receive OTP? <span onclick="resendOtp()">Resend OTP</span>
        </div>

        <div id="otpError" class="err-msg text-center d-none mb-2"></div>

        <button type="submit" class="btn-main" id="verifyBtn">
          VERIFY
        </button>
      </form>

      <p class="text-center mt-3 mb-0" style="font-size:.85rem;color:#888;">
        Wrong number?
        <a onclick="goBack()" style="color:var(--teal);font-weight:600;cursor:pointer;">Change</a>
      </p>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let timerInterval;

// ── Phone form submit → send OTP ──────────────────
document.getElementById('phoneForm').addEventListener('submit', async function(e) {
  e.preventDefault();
  const phone = document.getElementById('phoneInput').value.trim();
  const errEl = document.getElementById('phoneError');
  const btn   = document.getElementById('getOtpBtn');

  errEl.classList.add('d-none');

  if (!/^[6-9][0-9]{9}$/.test(phone)) {
    errEl.textContent = 'Enter a valid 10-digit WhatsApp number.';
    errEl.classList.remove('d-none');
    return;
  }

  btn.disabled = true;
  btn.textContent = 'Sending…';

  try {
    const res  = await fetch('{{ route("otp.send") }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify({ phone }),
    });
    const data = await res.json();

    if (data.success) {
      // document.getElementById('hiddenPhone').value = phone;
      document.getElementById('hiddenPhone').value = phone.replace(/\D/g, '').slice(-10);
      showStep2();
    } else {
      errEl.textContent = data.message || 'Failed to send OTP. Try again.';
      errEl.classList.remove('d-none');
    }
  } catch {
    errEl.textContent = 'Network error. Please try again.';
    errEl.classList.remove('d-none');
  } finally {
    btn.disabled = false;
    btn.textContent = 'GET OTP';
  }
});

// ── Show step 2 ──────────────────────────────────
function showStep2() {
  document.getElementById('step1').classList.add('d-none');
  document.getElementById('step2').classList.remove('d-none');
  document.querySelector('.otp-box').focus();
  startTimer(30);
}

function goBack() {
  clearInterval(timerInterval);
  document.getElementById('step2').classList.add('d-none');
  document.getElementById('step1').classList.remove('d-none');
  document.querySelectorAll('.otp-box').forEach(b => b.value = '');
}

// ── OTP box auto-jump ─────────────────────────────
document.querySelectorAll('.otp-box').forEach((box, i, boxes) => {
  box.addEventListener('input', function() {
    this.value = this.value.replace(/\D/g, '');
    if (this.value && i < boxes.length - 1) boxes[i + 1].focus();
    collectOtp();
  });
  box.addEventListener('keydown', function(e) {
    if (e.key === 'Backspace' && !this.value && i > 0) boxes[i - 1].focus();
  });
  box.addEventListener('paste', function(e) {
    e.preventDefault();
    const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
    [...text].slice(0, 4).forEach((ch, j) => { if (boxes[i + j]) boxes[i + j].value = ch; });
    collectOtp();
    if (boxes[Math.min(i + text.length, 3)]) boxes[Math.min(i + text.length, 3)].focus();
  });
});

function collectOtp() {
  document.getElementById('otpValue').value =
    [...document.querySelectorAll('.otp-box')].map(b => b.value).join('');
}

// ── Timer ─────────────────────────────────────────
function startTimer(seconds) {
  clearInterval(timerInterval);
  document.getElementById('resendArea').classList.remove('d-none');
  document.getElementById('resendBtn').classList.add('d-none');

  let remaining = seconds;
  document.getElementById('timerCount').textContent = remaining;

  timerInterval = setInterval(() => {
    remaining--;
    document.getElementById('timerCount').textContent = remaining;
    if (remaining <= 0) {
      clearInterval(timerInterval);
      document.getElementById('resendArea').classList.add('d-none');
      document.getElementById('resendBtn').classList.remove('d-none');
    }
  }, 1000);
}

// ── Resend OTP ────────────────────────────────────
async function resendOtp() {
  const phone = document.getElementById('hiddenPhone').value;
  document.getElementById('resendBtn').classList.add('d-none');
  document.getElementById('resendArea').classList.remove('d-none');
  document.getElementById('timerCount').textContent = '…';

  await fetch('{{ route("otp.send") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': csrfToken,
      'Accept': 'application/json',
    },
    body: JSON.stringify({ phone }),
  });

  document.querySelectorAll('.otp-box').forEach(b => b.value = '');
  startTimer(30);
}

// ── OTP form submit ───────────────────────────────
document.getElementById('otpForm').addEventListener('submit', function(e) {
  const otp = document.getElementById('otpValue').value;
  const errEl = document.getElementById('otpError');
  errEl.classList.add('d-none');

  if (otp.length < 4) {
    e.preventDefault();
    errEl.textContent = 'Please enter the 4-digit OTP.';
    errEl.classList.remove('d-none');
    return;
  }

  document.getElementById('verifyBtn').disabled = true;
  document.getElementById('verifyBtn').textContent = 'Verifying…';
});
</script>
</body>
</html>
