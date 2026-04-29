{{-- ══════════════════════════════════════════════
     web/hospitals/index.blade.php
══════════════════════════════════════════════ --}}
@extends('layouts.web')
@section('title','All Hospitals')

@push('styles')
<style>
  .page-hero { background:linear-gradient(135deg,#0d1b3e,#132044); padding:2.5rem 0; }
  .page-hero h1 { color:#fff; font-weight:800; }
  .page-hero p  { color:rgba(255,255,255,.7); }
  .filter-card  { background:#fff; border-radius:14px; padding:1.2rem; box-shadow:0 4px 16px rgba(0,0,0,.07); }
  .hosp-card    { background:#fff; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,.07); overflow:hidden; transition:transform .2s,box-shadow .2s; }
  .hosp-card:hover { transform:translateY(-4px); box-shadow:0 12px 32px rgba(0,0,0,.13); }
  .hosp-img { height:120px; background:linear-gradient(135deg,#0d1b3e,#0f6e56); display:flex; align-items:center; justify-content:center; font-size:2.8rem; }
  .hosp-body { padding:1rem 1.2rem 1.2rem; }
  .btn-book { background:#0f9b82; color:#fff; border:none; border-radius:20px; padding:.4rem 1.1rem; font-size:.82rem; font-weight:700; text-decoration:none; }
  .btn-book:hover { background:#0d8470; color:#fff; }
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="container">
    <h1>All Hospitals</h1>
    <p>Find the best hospitals near you</p>
  </div>
</div>

<div class="container py-4">
  {{-- Filters --}}
  <div class="filter-card mb-4">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-5">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control" placeholder="Search hospital…">
      </div>
      <div class="col-sm-4">
        <select name="city" class="form-select" onchange="this.form.submit()">
          <option value="">All Cities</option>
          @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city') == $city ? 'selected':'' }}>{{ $city }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3">
        <button class="btn btn-sm w-100" style="background:#0f9b82;color:#fff;border-radius:8px;">
          <i class="bi bi-search me-1"></i> Search
        </button>
      </div>
    </form>
  </div>

  <div class="row g-3">
    @forelse($hospitals as $hospital)
    <div class="col-sm-6 col-lg-4">
      <div class="hosp-card h-100">
        <div class="hosp-img">
          @if($hospital->logo)
            <img src="{{ asset('storage/'.$hospital->logo) }}" style="width:70px;height:70px;object-fit:cover;border-radius:10px;">
          @else 🏥 @endif
        </div>
        <div class="hosp-body d-flex flex-column h-100" style="min-height:140px;">
          <div class="fw-bold mb-1">{{ $hospital->name }}</div>
          <div class="text-muted small mb-2">
            <i class="bi bi-geo-alt me-1"></i>{{ $hospital->city }}, {{ $hospital->state }}
          </div>
          <div class="d-flex gap-2 flex-wrap mb-3">
            <span style="background:#e8f8f4;color:#0f6e56;border-radius:20px;font-size:.75rem;font-weight:600;padding:.2rem .7rem;">
              {{ $hospital->doctors_count }} Doctors
            </span>
            <span style="background:#e8f8f4;color:#0f6e56;border-radius:20px;font-size:.75rem;font-weight:600;padding:.2rem .7rem;">
              {{ $hospital->departments_count }} Departments
            </span>
          </div>
          <div class="mt-auto">
            <a href="{{ route('hospitals.show', $hospital) }}" class="btn-book">
              View Doctors →
            </a>
          </div>
        </div>
      </div>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">No hospitals found.</div>
    @endforelse
  </div>

  <div class="mt-4 d-flex justify-content-center">
    {{ $hospitals->links() }}
  </div>
</div>
@endsection
