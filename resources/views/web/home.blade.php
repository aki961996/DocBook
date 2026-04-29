@extends('layouts.web')
@section('title', 'DocBook — Book Doctors Online')

@push('styles')
<style>
  /* ── Hero ── */
  .hero {
    background: linear-gradient(135deg, #0d1b3e 0%, #132044 60%, #0f3460 100%);
    padding: 4rem 0 5rem;
    position: relative; overflow: hidden;
  }
  .hero::after {
    content: '';
    position: absolute; bottom: -1px; left: 0; right: 0;
    height: 60px;
    background: #f5f7fb;
    clip-path: ellipse(55% 100% at 50% 100%);
  }
  .hero h1 { color: #fff; font-weight: 800; font-size: clamp(1.6rem, 4vw, 2.6rem); }
  .hero p  { color: rgba(255,255,255,.75); font-size: clamp(.9rem, 2vw, 1.1rem); }

  /* Search box */
  .search-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.2rem 1.4rem;
    box-shadow: 0 8px 32px rgba(0,0,0,.18);
    margin-top: 2rem;
  }
  .search-card .form-control,
  .search-card .form-select {
    border: 1.5px solid #e0e4ef;
    border-radius: 10px;
    padding: .65rem 1rem;
    font-size: .93rem;
  }
  .search-card .form-control:focus,
  .search-card .form-select:focus { border-color: #0f9b82; box-shadow: none; }
  .btn-search {
    background: #0f9b82; color: #fff; border: none;
    border-radius: 10px; padding: .65rem 1.6rem;
    font-weight: 700; width: 100%;
    transition: background .2s;
  }
  .btn-search:hover { background: #0d8470; }

  /* ── Stats ── */
  .stat-card {
    background: #fff; border-radius: 14px;
    padding: 1.5rem; text-align: center;
    box-shadow: 0 4px 16px rgba(0,0,0,.07);
  }
  .stat-num { font-size: 2rem; font-weight: 800; color: #0f9b82; }
  .stat-label { color: #666; font-size: .85rem; margin-top: .2rem; }

  /* ── Dept pills ── */
  .dept-pill {
    display: flex; flex-direction: column; align-items: center;
    gap: .5rem; cursor: pointer;
    text-decoration: none;
  }
  .dept-icon {
    width: 62px; height: 62px;
    border-radius: 50%;
    background: #e1f5ee;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    transition: transform .2s, background .2s;
    border: 2px solid transparent;
  }
  .dept-pill:hover .dept-icon {
    transform: scale(1.1);
    background: #0f9b82; border-color: #0f9b82;
  }
  .dept-pill:hover .dept-name { color: #0f9b82; }
  .dept-name { font-size: .78rem; font-weight: 600; color: #444; text-align: center; }

  /* ── Hospital card ── */
  .hosp-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,.07);
    overflow: hidden; height: 100%;
    transition: transform .2s, box-shadow .2s;
    border: none;
  }
  .hosp-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(0,0,0,.13); }
  .hosp-img {
    height: 130px; background: linear-gradient(135deg,#0d1b3e,#0f6e56);
    display: flex; align-items: center; justify-content: center;
    font-size: 3rem;
  }
  .hosp-body { padding: 1rem 1.2rem 1.2rem; }
  .hosp-name { font-weight: 700; font-size: 1rem; color: #1a1a2e; margin-bottom: .3rem; }
  .hosp-city { color: #888; font-size: .82rem; }
  .hosp-meta { display: flex; gap: .5rem; flex-wrap: wrap; margin-top: .6rem; }
  .hosp-badge {
    background: #e8f8f4; color: #0f6e56;
    border-radius: 20px; font-size: .75rem; font-weight: 600;
    padding: .2rem .7rem;
  }

  /* ── Doctor card ── */
  .doc-card {
    background: #fff; border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,.07);
    padding: 1.2rem; display: flex; gap: 1rem;
    align-items: flex-start;
    transition: transform .2s, box-shadow .2s;
    text-decoration: none; color: inherit;
  }
  .doc-card:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(0,0,0,.12); }
  .doc-avatar {
    width: 58px; height: 58px; border-radius: 50%; object-fit: cover;
    background: #e1f5ee; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: 1.1rem; color: #0f6e56;
  }
  .doc-name { font-weight: 700; font-size: .95rem; color: #1a1a2e; }
  .doc-spec { color: #0f9b82; font-size: .8rem; font-weight: 600; margin:.15rem 0; }
  .doc-dept { color: #888; font-size: .78rem; }
  .doc-fee  { font-weight: 700; color: #0f6e56; font-size: .88rem; margin-top: .4rem; }

  .section-title {
    font-weight: 800; font-size: 1.35rem; color: #1a1a2e;
  }
  .see-all {
    color: #0f9b82; font-weight: 600; text-decoration: none; font-size: .9rem;
  }
  .see-all:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

{{-- ── Hero ──────────────────────────────────────────── --}}
<section class="hero">
  <div class="container">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1>Find & Book<br>Trusted Doctors</h1>
        <p class="mt-2 mb-0">Book appointments instantly via WhatsApp OTP. No waiting, no hassle.</p>

        {{-- Search card --}}
        <div class="search-card">
          <form action="{{ route('doctors.index') }}" method="GET">
            <div class="row g-2">
              <div class="col-12">
                <input type="text" name="search" class="form-control"
                       placeholder="🔍  Search doctor, specialty…"
                       value="{{ request('search') }}">
              </div>
              <div class="col-sm-6">
                <select name="department_id" class="form-select">
                  <option value="">All Departments</option>
                  @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-sm-6">
                <select name="city" class="form-select">
                  <option value="">All Cities</option>
                  @foreach($cities as $city)
                    <option value="{{ $city }}">{{ $city }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-12">
                <button type="submit" class="btn-search">
                  <i class="bi bi-search me-1"></i> Search Doctors
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-6 d-none d-lg-flex justify-content-center mt-4 mt-lg-0">
        <div style="font-size:10rem;opacity:.18;">🏥</div>
      </div>
    </div>
  </div>
</section>

{{-- ── Stats ────────────────────────────────────────── --}}
<section class="py-4">
  <div class="container">
    <div class="row g-3 text-center">
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-num">{{ $stats['hospitals'] }}+</div>
          <div class="stat-label">Hospitals</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-num">{{ $stats['doctors'] }}+</div>
          <div class="stat-label">Doctors</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-num">{{ $stats['departments'] }}+</div>
          <div class="stat-label">Specialties</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="stat-card">
          <div class="stat-num">{{ $stats['bookings'] }}+</div>
          <div class="stat-label">Bookings Done</div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ── Departments ──────────────────────────────────── --}}
<section class="py-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="section-title mb-0">Browse by Specialty</h2>
      <a href="{{ route('doctors.index') }}" class="see-all">See all →</a>
    </div>
    <div class="row g-3 text-center">
      @php
        $deptIcons = [
          'General Medicine' => '🩺', 'Gynaecology' => '👶',
          'Cardiology' => '❤️', 'Orthopaedics' => '🦴',
          'Paediatrics' => '🧒', 'Dermatology' => '✨',
          'ENT' => '👂', 'Ophthalmology' => '👁️',
          'Neurology' => '🧠', 'Dental' => '🦷',
          'Urology' => '💊', 'Psychiatry' => '🧘',
          'Oncology' => '🔬', 'Physiotherapy' => '💪',
        ];
      @endphp
      @foreach($topDepartments as $dept)
      <div class="col-3 col-sm-2">
        <a href="{{ route('doctors.index', ['department_id' => $dept->id]) }}"
           class="dept-pill">
          <div class="dept-icon">
            {{ $deptIcons[$dept->name] ?? '🏥' }}
          </div>
          <span class="dept-name">{{ Str::limit($dept->name, 12) }}</span>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ── Featured Hospitals ───────────────────────────── --}}
<section class="py-4" style="background:#fff;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="section-title mb-0">Top Hospitals</h2>
      <a href="{{ route('hospitals.index') }}" class="see-all">See all →</a>
    </div>
    <div class="row g-3">
      @forelse($featuredHospitals as $hospital)
      <div class="col-sm-6 col-lg-4">
        <a href="{{ route('hospitals.show', $hospital) }}" style="text-decoration:none;">
          <div class="hosp-card">
            <div class="hosp-img">
              @if($hospital->logo)
                <img src="{{ asset('storage/'.$hospital->logo) }}"
                     style="width:80px;height:80px;object-fit:cover;border-radius:12px;">
              @else
                🏥
              @endif
            </div>
            <div class="hosp-body">
              <div class="hosp-name">{{ $hospital->name }}</div>
              <div class="hosp-city">
                <i class="bi bi-geo-alt me-1"></i>{{ $hospital->city }}, {{ $hospital->state }}
              </div>
              <div class="hosp-meta">
                <span class="hosp-badge">
                  <i class="bi bi-person-badge me-1"></i>{{ $hospital->doctors_count }} Doctors
                </span>
                <span class="hosp-badge">
                  <i class="bi bi-grid me-1"></i>{{ $hospital->departments_count }} Depts
                </span>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty
        <div class="col-12 text-center text-muted py-4">No hospitals added yet.</div>
      @endforelse
    </div>
  </div>
</section>

{{-- ── Featured Doctors ─────────────────────────────── --}}
<section class="py-4">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h2 class="section-title mb-0">Top Doctors</h2>
      <a href="{{ route('doctors.index') }}" class="see-all">See all →</a>
    </div>
    <div class="row g-3">
      @forelse($featuredDoctors as $doctor)
      <div class="col-sm-6 col-lg-4">
        <a href="{{ route('doctors.show', $doctor) }}" class="doc-card">
          <div class="doc-avatar">
            @if($doctor->photo)
              <img src="{{ asset('storage/'.$doctor->photo) }}"
                   style="width:58px;height:58px;border-radius:50%;object-fit:cover;">
            @else
              {{ strtoupper(substr($doctor->name,3,2)) }}
            @endif
          </div>
          <div class="flex-grow-1">
            <div class="doc-name">{{ $doctor->name }}</div>
            <div class="doc-spec">{{ $doctor->specialization }}</div>
            <div class="doc-dept">
              <i class="bi bi-building me-1"></i>{{ $doctor->hospital->name }}
            </div>
            <div class="doc-fee">₹ {{ number_format($doctor->consultation_fee) }} / visit</div>
          </div>
        </a>
      </div>
      @empty
        <div class="col-12 text-center text-muted py-4">No doctors added yet.</div>
      @endforelse
    </div>
  </div>
</section>

{{-- ── CTA ──────────────────────────────────────────── --}}
@guest('web')
<section class="py-5" style="background:linear-gradient(135deg,#0d1b3e,#0f3460);">
  <div class="container text-center">
    <h2 class="text-white fw-bold mb-2">Ready to Book Your Appointment?</h2>
    <p class="text-white-50 mb-4">Login with your WhatsApp number — takes only 30 seconds.</p>
    <a href="{{ route('login') }}" class="btn btn-lg"
       style="background:#0f9b82;color:#fff;border-radius:50px;padding:.75rem 2.5rem;font-weight:700;">
      <i class="bi bi-whatsapp me-2"></i> Login with WhatsApp OTP
    </a>
  </div>
</section>
@endguest

@endsection
