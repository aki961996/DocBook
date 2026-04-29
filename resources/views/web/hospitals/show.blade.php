@extends('layouts.web')
@section('title', $hospital->name)

@push('styles')
<style>
  .hosp-hero { background:linear-gradient(135deg,#0d1b3e,#0f3460); padding:2.5rem 0; }
  .hosp-logo { width:80px;height:80px;border-radius:16px;object-fit:cover;background:#fff;padding:4px; }
  .dept-tab { background:#fff;border:2px solid #e0e4ef;border-radius:24px;padding:.35rem 1rem;font-size:.85rem;font-weight:600;color:#555;cursor:pointer;text-decoration:none;transition:all .2s; }
  .dept-tab:hover,.dept-tab.active { background:#0f9b82;border-color:#0f9b82;color:#fff; }
  .doc-row { background:#fff;border-radius:14px;box-shadow:0 2px 12px rgba(0,0,0,.06);padding:1rem 1.2rem;display:flex;align-items:center;gap:1rem;margin-bottom:.8rem; }
  .doc-ava { width:56px;height:56px;border-radius:50%;object-fit:cover;background:#e1f5ee;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0f6e56;flex-shrink:0; }
  .btn-slot { background:#0f9b82;color:#fff;border:none;border-radius:20px;padding:.38rem 1.1rem;font-size:.82rem;font-weight:700;text-decoration:none;white-space:nowrap; }
  .btn-slot:hover { background:#0d8470;color:#fff; }
</style>
@endpush

@section('content')
<div class="hosp-hero">
  <div class="container">
    <div class="d-flex align-items-center gap-3">
      @if($hospital->logo)
        <img src="{{ asset('storage/'.$hospital->logo) }}" class="hosp-logo">
      @else
        <div class="hosp-logo d-flex align-items-center justify-content-center" style="font-size:2rem;">🏥</div>
      @endif
      <div>
        <h1 class="text-white fw-bold mb-1" style="font-size:clamp(1.2rem,3vw,1.8rem);">{{ $hospital->name }}</h1>
        <p class="mb-1" style="color:rgba(255,255,255,.7);font-size:.9rem;">
          <i class="bi bi-geo-alt me-1"></i>{{ $hospital->address }}, {{ $hospital->city }}
        </p>
        <div class="d-flex gap-3" style="color:rgba(255,255,255,.8);font-size:.85rem;">
          <span><i class="bi bi-telephone me-1"></i>{{ $hospital->phone }}</span>
          <span><i class="bi bi-people me-1"></i>{{ $hospital->doctors->count() }} Doctors</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="container py-4">
  {{-- Department filter tabs --}}
  <div class="d-flex gap-2 flex-wrap mb-4">
    <a href="{{ route('hospitals.show', $hospital) }}"
       class="dept-tab {{ !request('dept') ? 'active' : '' }}">All</a>
    @foreach($hospital->departments as $dept)
      <a href="{{ route('hospitals.show', [$hospital, 'dept' => $dept->id]) }}"
         class="dept-tab {{ request('dept') == $dept->id ? 'active' : '' }}">
        {{ $dept->name }}
      </a>
    @endforeach
  </div>

  {{-- Doctors --}}
  @forelse($doctors as $doctor)
  <div class="doc-row flex-wrap">
    <div class="doc-ava">
      @if($doctor->photo)
        <img src="{{ asset('storage/'.$doctor->photo) }}" style="width:56px;height:56px;border-radius:50%;object-fit:cover;">
      @else
        {{ strtoupper(substr($doctor->name,3,2)) }}
      @endif
    </div>
    <div class="flex-grow-1 min-w-0">
      <div class="fw-bold" style="font-size:.95rem;">{{ $doctor->name }}</div>
      <div style="color:#0f9b82;font-size:.8rem;font-weight:600;">{{ $doctor->specialization }}</div>
      <div class="text-muted" style="font-size:.78rem;">{{ $doctor->department->name }} • {{ $doctor->experience_years }} yrs exp</div>
    </div>
    <div class="text-end flex-shrink-0">
      <div class="fw-bold mb-1" style="color:#0f6e56;">₹{{ number_format($doctor->consultation_fee) }}</div>
      <a href="{{ route('doctors.show', $doctor) }}" class="btn-slot">Book Slot</a>
    </div>
  </div>
  @empty
  <div class="text-center text-muted py-5">No doctors in this department.</div>
  @endforelse
</div>
@endsection
