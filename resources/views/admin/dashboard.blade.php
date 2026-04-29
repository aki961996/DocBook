@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row row-deck row-cards mt-2">

  {{-- ── Stat Cards ───────────────────────────────────── --}}
  @if(auth('admin')->user()->isSuperAdmin())
  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="subheader">Total Hospitals</div>
        </div>
        <div class="h1 mb-3">{{ $stats['hospitals'] }}</div>
        <div class="d-flex mb-2">
          <a href="{{ route('admin.hospitals.index') }}" class="text-muted small">View all →</a>
        </div>
      </div>
    </div>
  </div>
  @endif

  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="subheader">Doctors</div>
        </div>
        <div class="h1 mb-3">{{ $stats['doctors'] }}</div>
        <div class="d-flex mb-2">
          <a href="{{ route('admin.doctors.index') }}" class="text-muted small">View all →</a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="subheader">Bookings Today</div>
        </div>
        <div class="h1 mb-3">{{ $stats['bookings_today'] }}</div>
        <div class="d-flex mb-2">
          <span class="text-muted small">{{ now()->format('d M Y') }}</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="subheader">Pending Bookings</div>
        </div>
        <div class="h1 mb-3" style="color:#e67e22;">{{ $stats['pending_bookings'] }}</div>
        <div class="d-flex mb-2">
          <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}"
             class="text-muted small">Review now →</a>
        </div>
      </div>
    </div>
  </div>

</div>

{{-- ── Recent Bookings Table ────────────────────────── --}}
<div class="card mt-4">
  <div class="card-header">
    <h3 class="card-title"><i class="ti ti-calendar-check me-2"></i>Recent Bookings</h3>
    <div class="card-options">
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-secondary">
        View all
      </a>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover">
      <thead>
        <tr>
          <th>Token</th>
          <th>Patient</th>
          <th>Doctor</th>
          @if(auth('admin')->user()->isSuperAdmin())
          <th>Hospital</th>
          @endif
          <th>Date & Time</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentBookings as $booking)
        <tr>
          <td>
            <code class="small">{{ $booking->booking_token }}</code>
          </td>
          <td>
            <div>{{ $booking->patient_name }}</div>
            <div class="text-muted small">{{ $booking->patient_phone }}</div>
          </td>
          <td>
            <div>{{ $booking->doctor->name }}</div>
            <div class="text-muted small">{{ $booking->doctor->department->name ?? '' }}</div>
          </td>
          @if(auth('admin')->user()->isSuperAdmin())
          <td>{{ $booking->hospital->name }}</td>
          @endif
          <td>
            <div>{{ $booking->slot->slot_date->format('d M Y') }}</div>
            <div class="text-muted small">
              {{ \Carbon\Carbon::parse($booking->slot->start_time)->format('h:i A') }}
            </div>
          </td>
          <td>
            <span class="badge badge-status-{{ $booking->status }}">
              {{ ucfirst($booking->status) }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.bookings.show', $booking) }}"
               class="btn btn-sm btn-outline-primary">View</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center text-muted py-4">No bookings yet</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
