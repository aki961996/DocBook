@extends('admin.layouts.app')
@section('title','Booking #'.$booking->booking_token)
@section('page-title','Booking Detail')

@section('page-actions')
  <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection

@section('content')
<div class="row">

  {{-- ── Booking Info ─────────────────────────────── --}}
  <div class="col-lg-8">
    <div class="card mb-3">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title mb-0">
          <i class="ti ti-calendar-check me-2"></i>
          Booking <code>{{ $booking->booking_token }}</code>
        </h3>
        <span class="badge badge-status-{{ $booking->status }} fs-6">
          {{ ucfirst($booking->status) }}
        </span>
      </div>
      <div class="card-body">
        <div class="row g-3">

          <div class="col-md-6">
            <label class="text-muted small d-block">Patient Name</label>
            <strong>{{ $booking->patient_name }}</strong>
          </div>
          <div class="col-md-6">
            <label class="text-muted small d-block">Phone</label>
            <strong>{{ $booking->patient_phone }}</strong>
          </div>
          @if($booking->patient_age)
          <div class="col-md-4">
            <label class="text-muted small d-block">Age</label>
            <strong>{{ $booking->patient_age }} yrs</strong>
          </div>
          @endif
          @if($booking->patient_gender)
          <div class="col-md-4">
            <label class="text-muted small d-block">Gender</label>
            <strong>{{ ucfirst($booking->patient_gender) }}</strong>
          </div>
          @endif
          <div class="col-12">
            <label class="text-muted small d-block">Reason for Visit</label>
            <p class="mb-0">{{ $booking->reason ?: '—' }}</p>
          </div>

          <div class="col-12"><hr class="my-1"></div>

          <div class="col-md-6">
            <label class="text-muted small d-block">Doctor</label>
            <strong>{{ $booking->doctor->name }}</strong><br>
            <span class="text-muted small">{{ $booking->doctor->qualification }}</span>
          </div>
          <div class="col-md-6">
            <label class="text-muted small d-block">Department</label>
            <strong>{{ $booking->doctor->department->name ?? '—' }}</strong>
          </div>
          <div class="col-md-6">
            <label class="text-muted small d-block">Appointment Date</label>
            <strong>{{ $booking->slot->slot_date->format('l, d M Y') }}</strong>
          </div>
          <div class="col-md-6">
            <label class="text-muted small d-block">Time Slot</label>
            <strong>
              {{ \Carbon\Carbon::parse($booking->slot->start_time)->format('h:i A') }}
              – {{ \Carbon\Carbon::parse($booking->slot->end_time)->format('h:i A') }}
            </strong>
          </div>
          @if(auth('admin')->user()->isSuperAdmin())
          <div class="col-12">
            <label class="text-muted small d-block">Hospital</label>
            <strong>{{ $booking->hospital->name }}</strong>
          </div>
          @endif
          <div class="col-md-6">
            <label class="text-muted small d-block">Booked At</label>
            <strong>{{ $booking->booked_at->format('d M Y, h:i A') }}</strong>
          </div>

          @if($booking->admin_notes)
          <div class="col-12">
            <label class="text-muted small d-block">Admin Notes</label>
            <p class="mb-0 fst-italic">{{ $booking->admin_notes }}</p>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- ── Update Status ─────────────────────────────── --}}
  <div class="col-lg-4">
    @if(!in_array($booking->status, ['completed','cancelled']))
    <div class="card">
      <div class="card-header"><h3 class="card-title">Update Status</h3></div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
          @csrf @method('PATCH')

          <div class="mb-3">
            <label class="form-label">New Status</label>
            <select name="status" class="form-select">
              @foreach(['confirmed','completed','cancelled'] as $s)
                <option value="{{ $s }}" {{ $booking->status == $s ? 'selected' : '' }}>
                  {{ ucfirst($s) }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Admin Notes</label>
            <textarea name="admin_notes" rows="3" class="form-control"
                      placeholder="Optional note…">{{ old('admin_notes', $booking->admin_notes) }}</textarea>
          </div>

          <button type="submit" class="btn w-100" style="background:#0f6e56;color:#fff;">
            <i class="ti ti-check me-1"></i> Update Status
          </button>
        </form>
      </div>
    </div>
    @else
    <div class="card">
      <div class="card-body text-center text-muted py-4">
        <i class="ti ti-lock" style="font-size:1.5rem;"></i>
        <p class="mt-2 mb-0">Booking is {{ $booking->status }}. No further changes.</p>
      </div>
    </div>
    @endif

    {{-- Patient info card --}}
    <div class="card mt-3">
      <div class="card-header"><h3 class="card-title">Patient Account</h3></div>
      <div class="card-body">
        <div class="text-muted small mb-1">Registered Phone</div>
        <strong>{{ $booking->user->phone }}</strong>
        @if($booking->user->email)
        <div class="text-muted small mb-1 mt-2">Email</div>
        <strong>{{ $booking->user->email }}</strong>
        @endif
        @if($booking->user->phone_verified_at)
        <div class="mt-2">
          <span class="badge bg-success">
            <i class="ti ti-check me-1"></i>WhatsApp Verified
          </span>
        </div>
        @endif
      </div>
    </div>
  </div>

</div>
@endsection
