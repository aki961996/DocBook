{{-- ══════════════════════════════════════════════
     bookings/index.blade.php
══════════════════════════════════════════════ --}}
@extends('admin.layouts.app')
@section('title','Bookings')
@section('page-title','Bookings')

@section('content')

{{-- Filters --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
      <select name="status" class="form-select form-select-sm" style="max-width:150px;"
              onchange="this.form.submit()">
        <option value="">All Status</option>
        @foreach(['pending','confirmed','completed','cancelled'] as $s)
          <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
            {{ ucfirst($s) }}
          </option>
        @endforeach
      </select>
      <input type="date" name="date" value="{{ request('date') }}"
             class="form-control form-control-sm" style="max-width:160px;"
             onchange="this.form.submit()">
      <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover">
      <thead>
        <tr>
          <th>Token</th>
          <th>Patient</th>
          <th>Doctor</th>
          @if(auth('admin')->user()->isSuperAdmin())<th>Hospital</th>@endif
          <th>Slot</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($bookings as $booking)
        <tr>
          <td><code class="small">{{ $booking->booking_token }}</code></td>
          <td>
            <div>{{ $booking->patient_name }}</div>
            <div class="text-muted small">{{ $booking->patient_phone }}</div>
          </td>
          <td>
            <div>{{ $booking->doctor->name }}</div>
            <div class="text-muted small">{{ $booking->doctor->department->name ?? '' }}</div>
          </td>
          @if(auth('admin')->user()->isSuperAdmin())
          <td class="text-muted small">{{ $booking->hospital->name }}</td>
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
          <td colspan="7" class="text-center text-muted py-5">No bookings found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($bookings->hasPages())
  <div class="card-footer d-flex justify-content-end">{{ $bookings->links() }}</div>
  @endif
</div>
@endsection
