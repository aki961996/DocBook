@extends('layouts.web')
@section('title', 'My Bookings')

@push('styles')
<style>
  .page-hero{background:linear-gradient(135deg,#0d1b3e,#132044);padding:2rem 0;}
  .page-hero h1{color:#fff;font-weight:800;}
  .booking-card{background:#fff;border-radius:16px;box-shadow:0 3px 16px rgba(0,0,0,.08);padding:1.2rem 1.4rem;margin-bottom:1rem;}
  .status-pending{background:#fff8e1;color:#f59e0b;border-radius:20px;font-size:.78rem;font-weight:700;padding:.25rem .8rem;}
  .status-confirmed{background:#e8f8f4;color:#0f6e56;border-radius:20px;font-size:.78rem;font-weight:700;padding:.25rem .8rem;}
  .status-completed{background:#e8f5e9;color:#2e7d32;border-radius:20px;font-size:.78rem;font-weight:700;padding:.25rem .8rem;}
  .status-cancelled{background:#fce8e8;color:#c62828;border-radius:20px;font-size:.78rem;font-weight:700;padding:.25rem .8rem;}
  .token-badge{font-family:monospace;background:#f0f4ff;color:#3b4da8;border-radius:8px;padding:.2rem .6rem;font-size:.82rem;font-weight:700;}
</style>
@endpush

@section('content')
<div class="page-hero">
  <div class="container">
    <h1>My Bookings</h1>
    <p style="color:rgba(255,255,255,.7);">Track all your appointments</p>
  </div>
</div>

<div class="container py-4">
  {{-- Filter tabs --}}
  <div class="d-flex gap-2 mb-4 flex-wrap">
    @foreach(['all','pending','confirmed','completed','cancelled'] as $s)
    <a href="{{ route('bookings.index', $s !== 'all' ? ['status'=>$s] : []) }}"
       class="dept-tab {{ (request('status', 'all') == $s) ? 'active' : '' }}"
       style="border:2px solid #e0e4ef;border-radius:24px;padding:.3rem .9rem;font-size:.83rem;font-weight:600;color:#555;text-decoration:none;transition:all .2s;">
      {{ ucfirst($s) }}
    </a>
    @endforeach
  </div>

  @forelse($bookings as $booking)
  <div class="booking-card">
    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-2">
      <div>
        <span class="token-badge"># {{ $booking->booking_token }}</span>
        <span class="ms-2 status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
      </div>
      {{-- <div class="text-muted small">{{ $booking->booked_at->format('d M Y, h:i A') }}</div> --}}
      <div class="text-muted small">
  {{ \Carbon\Carbon::parse($booking->booked_at)->format('d M Y, h:i A') }}
</div>
    </div>

    <div class="d-flex flex-wrap gap-3 align-items-center">
      {{-- Doctor info --}}
      <div class="d-flex align-items-center gap-2 flex-grow-1">
        <div style="width:48px;height:48px;border-radius:50%;background:#e1f5ee;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0f6e56;flex-shrink:0;">
          @if($booking->doctor->photo)
            <img src="{{ asset('storage/'.$booking->doctor->photo) }}"
                 style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
          @else
            {{ strtoupper(substr($booking->doctor->name,3,2)) }}
          @endif
        </div>
        <div>
          <div class="fw-bold" style="font-size:.93rem;">{{ $booking->doctor->name }}</div>
          <div style="color:#0f9b82;font-size:.78rem;font-weight:600;">{{ $booking->doctor->specialization }}</div>
          <div class="text-muted" style="font-size:.75rem;">{{ $booking->hospital->name }}</div>
        </div>
      </div>

      {{-- Slot info --}}
      <div class="text-center px-3 py-2 rounded-3" style="background:#f0faf8;min-width:120px;">
        <div class="fw-bold" style="color:#0f6e56;font-size:.9rem;">
          {{-- {{ $booking->slot->slot_date->format('d M Y') }} --}}
          {{ \Carbon\Carbon::parse($booking->slot->slot_date)->format('d M Y') }}
        </div>
        <div style="color:#0f9b82;font-size:.82rem;font-weight:600;">
          {{ \Carbon\Carbon::parse($booking->slot->start_time)->format('h:i A') }}
        </div>
      </div>
    </div>

    {{-- Admin notes --}}
    @if($booking->admin_notes)
    <div class="mt-2 p-2 rounded-3" style="background:#f8f9ff;font-size:.82rem;color:#555;">
      <i class="bi bi-info-circle me-1"></i>{{ $booking->admin_notes }}
    </div>
    @endif
  </div>
  @empty
  <div class="text-center py-5 text-muted">
    <i class="bi bi-calendar-x" style="font-size:3rem;opacity:.3;"></i>
    <p class="mt-3">No bookings yet.</p>
    <a href="{{ route('doctors.index') }}" class="btn btn-sm"
       style="background:#0f9b82;color:#fff;border-radius:20px;padding:.5rem 1.5rem;">
      Book a Doctor
    </a>
  </div>
  @endforelse

  <div class="mt-3 d-flex justify-content-center">{{ $bookings->links() }}</div>
</div>
@endsection
