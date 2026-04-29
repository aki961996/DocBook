{{-- ══════════════════════════════════════════════
     web/doctors/index.blade.php
══════════════════════════════════════════════ --}}
@extends('layouts.web')
@section('title','Find Doctors')

@push('styles')
<style>
  .page-hero{background:linear-gradient(135deg,#0d1b3e,#132044);padding:2rem 0;}
  .page-hero h1{color:#fff;font-weight:800;}
  .filter-wrap{background:#fff;border-radius:14px;padding:1.1rem;box-shadow:0 4px 14px rgba(0,0,0,.07);}
  .doc-card{background:#fff;border-radius:14px;box-shadow:0 3px 14px rgba(0,0,0,.07);padding:1.1rem;display:flex;gap:.9rem;align-items:flex-start;transition:transform .2s,box-shadow .2s;text-decoration:none;color:inherit;height:100%;}
  .doc-card:hover{transform:translateY(-3px);box-shadow:0 10px 28px rgba(0,0,0,.12);}
  .doc-ava{width:62px;height:62px;border-radius:50%;object-fit:cover;background:#e1f5ee;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:#0f6e56;flex-shrink:0;}
  .btn-book-sm{background:#0f9b82;color:#fff;border:none;border-radius:16px;padding:.3rem .9rem;font-size:.78rem;font-weight:700;margin-top:.5rem;}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="container"><h1>Find Doctors</h1></div>
</div>
<div class="container py-4">
  <div class="filter-wrap mb-4">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-sm-4">
        <input type="text" name="search" value="{{ request('search') }}"
               class="form-control form-control-sm" placeholder="Doctor name or specialty…">
      </div>
      <div class="col-sm-3">
        <select name="department_id" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Departments</option>
          @foreach($departments as $dept)
            <option value="{{ $dept->id }}" {{ request('department_id')==$dept->id?'selected':'' }}>{{ $dept->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-3">
        <select name="city" class="form-select form-select-sm" onchange="this.form.submit()">
          <option value="">All Cities</option>
          @foreach($cities as $city)
            <option value="{{ $city }}" {{ request('city')==$city?'selected':'' }}>{{ $city }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-sm-2">
        <button class="btn btn-sm w-100" style="background:#0f9b82;color:#fff;border-radius:8px;">Search</button>
      </div>
    </form>
  </div>

  <div class="row g-3">
    @forelse($doctors as $doctor)
    <div class="col-sm-6 col-lg-4">
      <a href="{{ route('doctors.show', $doctor) }}" class="doc-card">
        <div class="doc-ava">
          @if($doctor->photo)
            <img src="{{ asset('storage/'.$doctor->photo) }}" style="width:62px;height:62px;border-radius:50%;object-fit:cover;">
          @else {{ strtoupper(substr($doctor->name,3,2)) }} @endif
        </div>
        <div class="flex-grow-1">
          <div class="fw-bold" style="font-size:.93rem;">{{ $doctor->name }}</div>
          <div style="color:#0f9b82;font-size:.78rem;font-weight:600;">{{ $doctor->specialization }}</div>
          <div class="text-muted" style="font-size:.75rem;">{{ $doctor->department->name }}</div>
          <div class="text-muted" style="font-size:.75rem;"><i class="bi bi-building me-1"></i>{{ $doctor->hospital->name }}</div>
          <div class="fw-bold mt-1" style="color:#0f6e56;font-size:.85rem;">₹{{ number_format($doctor->consultation_fee) }}</div>
          <button class="btn-book-sm">Book Now</button>
        </div>
      </a>
    </div>
    @empty
    <div class="col-12 text-center py-5 text-muted">No doctors found.</div>
    @endforelse
  </div>
  <div class="mt-4 d-flex justify-content-center">{{ $doctors->links() }}</div>
</div>
@endsection
