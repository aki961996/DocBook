@extends('admin.layouts.app')
@section('title','Slots — '.$doctor->name)
@section('page-title','Slots: Dr. '.$doctor->name)

@section('page-actions')
  <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back to Doctors
  </a>
@endsection

@section('content')
<div class="row">

  {{-- ── Bulk Create Form ─────────────────────────── --}}
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="ti ti-calendar-plus me-2"></i>Create Slots (Bulk)</h3>
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('admin.slots.bulk', $doctor) }}">
          @csrf

          <div class="mb-3">
            <label class="form-label required">From Date</label>
            <input type="date" name="from_date"
                   class="form-control @error('from_date') is-invalid @enderror"
                   value="{{ old('from_date', now()->format('Y-m-d')) }}"
                   min="{{ now()->format('Y-m-d') }}">
            @error('from_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="mb-3">
            <label class="form-label required">To Date</label>
            <input type="date" name="to_date"
                   class="form-control @error('to_date') is-invalid @enderror"
                   value="{{ old('to_date', now()->addDays(30)->format('Y-m-d')) }}"
                   min="{{ now()->format('Y-m-d') }}">
            @error('to_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label required">Start Time</label>
              <input type="time" name="start_time"
                     class="form-control @error('start_time') is-invalid @enderror"
                     value="{{ old('start_time', '09:00') }}">
              @error('start_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-6">
              <label class="form-label required">End Time</label>
              <input type="time" name="end_time"
                     class="form-control @error('end_time') is-invalid @enderror"
                     value="{{ old('end_time', '17:00') }}">
              @error('end_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label required">Slot Duration</label>
            <select name="duration_mins" class="form-select">
              @foreach([15 => '15 minutes', 20 => '20 minutes', 30 => '30 minutes', 45 => '45 minutes', 60 => '1 hour'] as $val => $lbl)
                <option value="{{ $val }}" {{ old('duration_mins', 30) == $val ? 'selected' : '' }}>
                  {{ $lbl }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label required">Working Days</label>
            <div class="d-flex flex-wrap gap-2">
              @php
                $days = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',0=>'Sun'];
                $oldDays = old('weekdays', [1,2,3,4,5]);
              @endphp
              @foreach($days as $num => $label)
                <label class="form-check form-check-inline">
                  <input class="form-check-input" type="checkbox"
                         name="weekdays[]" value="{{ $num }}"
                         {{ in_array($num, $oldDays) ? 'checked' : '' }}>
                  <span class="form-check-label">{{ $label }}</span>
                </label>
              @endforeach
            </div>
            @error('weekdays')<div class="text-danger small">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="btn w-100" style="background:#0f6e56;color:#fff;">
            <i class="ti ti-calendar-plus me-1"></i> Generate Slots
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- ── Slot Listing ─────────────────────────────── --}}
  <div class="col-lg-8">
    @forelse($slots as $date => $daySlots)
    <div class="card mb-3">
      <div class="card-header py-2">
        <h4 class="card-title mb-0">
          {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
          <span class="badge bg-secondary ms-2">{{ $daySlots->count() }} slots</span>
        </h4>
      </div>
      <div class="card-body py-2">
        <div class="d-flex flex-wrap gap-2">
          @foreach($daySlots as $slot)
          <div class="d-flex align-items-center gap-1 border rounded px-2 py-1
               {{ $slot->is_booked ? 'border-success bg-success-subtle' : '' }}
               {{ $slot->is_blocked ? 'border-danger bg-danger-subtle' : '' }}"
               style="font-size:.85rem;">
            <span>
              {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
            </span>
            @if($slot->is_booked)
              <span class="badge bg-success ms-1">Booked</span>
            @elseif($slot->is_blocked)
              <span class="badge bg-danger ms-1">Blocked</span>
            @else
              {{-- Block toggle --}}
              <button type="button"
                      class="btn btn-sm p-0 ms-1 text-secondary toggle-block"
                      data-url="{{ route('admin.slots.toggle-block', $slot) }}"
                      title="Block this slot">
                <i class="ti ti-ban"></i>
              </button>
              {{-- Delete --}}
              <form method="POST" action="{{ route('admin.slots.destroy', [$doctor, $slot]) }}"
                    style="display:inline;" onsubmit="return confirm('Delete this slot?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm p-0 ms-1 text-danger">
                  <i class="ti ti-x"></i>
                </button>
              </form>
            @endif
          </div>
          @endforeach
        </div>
      </div>
    </div>
    @empty
    <div class="card">
      <div class="card-body text-center text-muted py-5">
        <i class="ti ti-calendar-off" style="font-size:2rem;opacity:.3;"></i>
        <p class="mt-2">No upcoming slots. Use the form to generate slots.</p>
      </div>
    </div>
    @endforelse
  </div>

</div>
@endsection

@push('scripts')
<script>
document.querySelectorAll('.toggle-block').forEach(btn => {
  btn.addEventListener('click', function () {
    fetch(this.dataset.url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      }
    })
    .then(r => r.json())
    .then(() => location.reload());
  });
});
</script>
@endpush
