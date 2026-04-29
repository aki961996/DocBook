@extends('layouts.web')
@section('title', $doctor->name)

@push('styles')
<style>
  .doc-hero{background:linear-gradient(135deg,#0d1b3e,#132044);padding:2.5rem 0;}
  .doc-profile-card{background:#fff;border-radius:20px;box-shadow:0 8px 32px rgba(0,0,0,.12);padding:1.5rem;margin-top:-40px;position:relative;z-index:2;}
  .doc-big-ava{width:90px;height:90px;border-radius:50%;object-fit:cover;background:#e1f5ee;display:flex;align-items:center;justify-content:center;font-size:1.8rem;font-weight:700;color:#0f6e56;border:4px solid #fff;box-shadow:0 4px 16px rgba(0,0,0,.15);}
  .date-btn{border:2px solid #e0e4ef;border-radius:12px;padding:.5rem .9rem;background:#fff;cursor:pointer;text-align:center;font-size:.82rem;font-weight:600;color:#444;transition:all .2s;min-width:70px;}
  .date-btn:hover,.date-btn.active{border-color:#0f9b82;background:#0f9b82;color:#fff;}
  .date-day{font-size:.7rem;color:inherit;opacity:.8;}
  .slot-btn{border:2px solid #0f9b82;color:#0f9b82;border-radius:20px;padding:.35rem 1rem;background:#fff;font-size:.82rem;font-weight:700;cursor:pointer;transition:all .2s;}
  .slot-btn:hover,.slot-btn.selected{background:#0f9b82;color:#fff;}
  .slot-btn:disabled{border-color:#ddd;color:#bbb;cursor:not-allowed;background:#f8f8f8;}
  .btn-confirm{background:#0f9b82;color:#fff;border:none;border-radius:50px;padding:.75rem 2rem;font-weight:700;font-size:1rem;width:100%;}
  .btn-confirm:hover{background:#0d8470;}
</style>
@endpush

@section('content')
<div class="doc-hero">
  <div class="container"><div style="height:50px;"></div></div>
</div>

<div class="container pb-5">
  {{-- Profile card --}}
  <div class="doc-profile-card mb-4">
    <div class="d-flex flex-wrap gap-3 align-items-start">
      <div class="doc-big-ava flex-shrink-0">
        @if($doctor->photo)
          <img src="{{ asset('storage/'.$doctor->photo) }}"
               style="width:90px;height:90px;border-radius:50%;object-fit:cover;">
        @else
          {{ strtoupper(substr($doctor->name,3,2)) }}
        @endif
      </div>
      <div class="flex-grow-1">
        <h1 class="fw-bold mb-1" style="font-size:clamp(1.1rem,3vw,1.5rem);">{{ $doctor->name }}</h1>
        <div style="color:#0f9b82;font-weight:600;font-size:.9rem;">{{ $doctor->specialization }}</div>
        <div class="text-muted small">{{ $doctor->qualification }} · {{ $doctor->experience_years }} yrs experience</div>
        <div class="text-muted small"><i class="bi bi-building me-1"></i>{{ $doctor->hospital->name }}</div>
        <div class="d-flex gap-3 mt-2 flex-wrap">
          <span style="background:#e8f8f4;color:#0f6e56;border-radius:20px;font-size:.8rem;font-weight:600;padding:.25rem .8rem;">
            <i class="bi bi-grid me-1"></i>{{ $doctor->department->name }}
          </span>
          <span style="background:#e8f8f4;color:#0f6e56;border-radius:20px;font-size:.8rem;font-weight:600;padding:.25rem .8rem;">
            ₹{{ number_format($doctor->consultation_fee) }} fee
          </span>
        </div>
      </div>
    </div>
    @if($doctor->bio)
    <p class="text-muted mt-3 mb-0" style="font-size:.88rem;">{{ $doctor->bio }}</p>
    @endif
  </div>

  <div class="row g-4">
    {{-- Slot booking --}}
    <div class="col-lg-7">
      <div class="bg-white rounded-4 p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Select Appointment Slot</h5>

        @if($slots->isEmpty())
          <div class="text-center text-muted py-4">
            <i class="bi bi-calendar-x" style="font-size:2rem;opacity:.4;"></i>
            <p class="mt-2">No available slots at this time.</p>
          </div>
        @else
        {{-- Date selector --}}
        <div class="d-flex gap-2 overflow-auto pb-2 mb-3" id="dateTabs" style="scrollbar-width:none;">
          @foreach($slots->keys() as $i => $date)
          <button type="button"
                  class="date-btn {{ $i === 0 ? 'active' : '' }}"
                  onclick="showDate('{{ $date }}', this)"
                  style="flex-shrink:0;">
            <div class="date-day">{{ \Carbon\Carbon::parse($date)->format('D') }}</div>
            <div>{{ \Carbon\Carbon::parse($date)->format('d') }}</div>
            <div class="date-day">{{ \Carbon\Carbon::parse($date)->format('M') }}</div>
          </button>
          @endforeach
        </div>

        {{-- Slots per date --}}
        @foreach($slots as $date => $daySlots)
        <div class="slot-day d-none" id="slots_{{ $date }}">
          <p class="text-muted small mb-2">
            {{ \Carbon\Carbon::parse($date)->format('l, d M Y') }} — {{ $daySlots->count() }} slot(s)
          </p>
          <div class="d-flex flex-wrap gap-2" id="slotBtns_{{ $date }}">
            @foreach($daySlots as $slot)
            <button type="button"
                    class="slot-btn"
                    onclick="selectSlot('{{ $slot->id }}', '{{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}', '{{ $date }}', this)">
              {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
            </button>
            @endforeach
          </div>
        </div>
        @endforeach
        @endif

        {{-- Hidden selected slot --}}
        <input type="hidden" id="selectedSlotId">
        <div id="selectedInfo" class="mt-3 p-3 rounded-3 d-none"
             style="background:#e8f8f4;border:1.5px solid #0f9b82;">
          <div class="fw-bold" style="color:#0f6e56;">
            ✅ Selected: <span id="selectedSlotText"></span>
          </div>
        </div>
      </div>
    </div>

    {{-- Booking form --}}
    <div class="col-lg-5">
      <div class="bg-white rounded-4 p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Your Details</h5>

        @guest('web')
        <div class="alert alert-warning rounded-3 border-0 mb-3" style="background:#fff8e1;">
          <i class="bi bi-whatsapp me-2" style="color:#25d366;"></i>
          <strong>Login required</strong> to book.
          <a href="{{ route('login') }}" style="color:#0f9b82;font-weight:700;">Login now →</a>
        </div>
        @endguest

        <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
          @csrf
          <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
          <input type="hidden" name="slot_id" id="formSlotId">

          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.88rem;">Patient Name</label>
            <input type="text" name="patient_name" class="form-control"
                   value="{{ auth('web')->user()?->name }}"
                   placeholder="Full name" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold" style="font-size:.88rem;">Phone</label>
            <input type="text" name="patient_phone" class="form-control"
                   value="{{ auth('web')->user()?->phone }}"
                   placeholder="+91 9876543210" required>
          </div>
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.88rem;">Age</label>
              <input type="number" name="patient_age" class="form-control" placeholder="25" min="0" max="120">
            </div>
            <div class="col-6">
              <label class="form-label fw-semibold" style="font-size:.88rem;">Gender</label>
              <select name="patient_gender" class="form-select">
                <option value="">Select</option>
                <option>Male</option><option>Female</option><option>Other</option>
              </select>
            </div>
          </div>
          <div class="mb-4">
            <label class="form-label fw-semibold" style="font-size:.88rem;">Reason for Visit</label>
            <textarea name="reason" class="form-control" rows="2" placeholder="Brief description…"></textarea>
          </div>

          @auth('web')
          <button type="submit" class="btn-confirm" id="confirmBtn" disabled>
            Select a slot to continue
          </button>
          @else
          <a href="{{ route('login') }}" class="btn-confirm d-block text-center text-decoration-none">
            <i class="bi bi-whatsapp me-1"></i> Login to Book
          </a>
          @endauth
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Show first date on load
document.addEventListener('DOMContentLoaded', function() {
  const first = document.querySelector('.slot-day');
  if (first) first.classList.remove('d-none');
});

function showDate(date, btn) {
  document.querySelectorAll('.slot-day').forEach(d => d.classList.add('d-none'));
  document.querySelectorAll('.date-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('slots_' + date)?.classList.remove('d-none');
  btn.classList.add('active');
  // Clear selection when date changes
  document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
  clearSelection();
}

function selectSlot(slotId, timeText, date, btn) {
  document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  document.getElementById('selectedSlotId').value = slotId;
  document.getElementById('formSlotId').value = slotId;
  const dateFormatted = new Date(date).toLocaleDateString('en-IN', {day:'numeric',month:'short',year:'numeric'});
  document.getElementById('selectedSlotText').textContent = timeText + ', ' + dateFormatted;
  document.getElementById('selectedInfo').classList.remove('d-none');
  const confirmBtn = document.getElementById('confirmBtn');
  if (confirmBtn) {
    confirmBtn.disabled = false;
    confirmBtn.textContent = 'Confirm Appointment';
  }
}

function clearSelection() {
  document.getElementById('selectedSlotId').value = '';
  document.getElementById('formSlotId').value = '';
  document.getElementById('selectedInfo').classList.add('d-none');
  const confirmBtn = document.getElementById('confirmBtn');
  if (confirmBtn) { confirmBtn.disabled = true; confirmBtn.textContent = 'Select a slot to continue'; }
}
</script>
@endpush
