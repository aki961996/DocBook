{{-- admin/doctors/_form.blade.php --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Doctor Details</h3></div>
        <div class="card-body">
          <div class="row g-3">

            {{-- Hospital (locked on edit) --}}
            <div class="col-md-6">
              <label class="form-label required">Hospital</label>
              <select name="hospital_id" id="hospitalSelect"
                      class="form-select @error('hospital_id') is-invalid @enderror"
                      {{ $doctor ? 'disabled' : '' }}>
                <option value="">— Select Hospital —</option>
                @foreach($hospitals as $id => $name)
                  <option value="{{ $id }}"
                          {{ old('hospital_id', $doctor?->hospital_id) == $id ? 'selected' : '' }}>
                    {{ $name }}
                  </option>
                @endforeach
              </select>
              @if($doctor)
                <input type="hidden" name="hospital_id" value="{{ $doctor->hospital_id }}">
              @endif
              @error('hospital_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Department (AJAX loaded) --}}
            <div class="col-md-6">
              <label class="form-label required">Department</label>
              <select name="department_id" id="deptSelect"
                      class="form-select @error('department_id') is-invalid @enderror">
                <option value="">— Select Department —</option>
                @foreach($departments as $id => $name)
                  <option value="{{ $id }}"
                          {{ old('department_id', $doctor?->department_id) == $id ? 'selected' : '' }}>
                    {{ $name }}
                  </option>
                @endforeach
              </select>
              @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-8">
              <label class="form-label required">Full Name</label>
              <input type="text" name="name"
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $doctor?->name) }}" placeholder="Dr. Priya Menon">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Qualification</label>
              <input type="text" name="qualification"
                     class="form-control @error('qualification') is-invalid @enderror"
                     value="{{ old('qualification', $doctor?->qualification) }}" placeholder="MBBS, MD">
              @error('qualification')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-8">
              <label class="form-label required">Specialization</label>
              <input type="text" name="specialization"
                     class="form-control @error('specialization') is-invalid @enderror"
                     value="{{ old('specialization', $doctor?->specialization) }}"
                     placeholder="e.g. Fetal Medicine">
              @error('specialization')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Experience (yrs)</label>
              <input type="number" name="experience_years" min="0" max="60"
                     class="form-control @error('experience_years') is-invalid @enderror"
                     value="{{ old('experience_years', $doctor?->experience_years) }}" placeholder="10">
              @error('experience_years')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Consultation Fee (₹)</label>
              <div class="input-group">
                <span class="input-group-text">₹</span>
                <input type="number" name="consultation_fee" min="0" step="10"
                       class="form-control @error('consultation_fee') is-invalid @enderror"
                       value="{{ old('consultation_fee', $doctor?->consultation_fee) }}" placeholder="500">
              </div>
              @error('consultation_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label">Phone</label>
              <input type="text" name="phone"
                     class="form-control"
                     value="{{ old('phone', $doctor?->phone) }}" placeholder="+91 9876543210">
            </div>

            <div class="col-md-4">
              <label class="form-label">Email</label>
              <input type="email" name="email"
                     class="form-control"
                     value="{{ old('email', $doctor?->email) }}" placeholder="doctor@hospital.com">
            </div>

            <div class="col-12">
              <label class="form-label">Bio</label>
              <textarea name="bio" rows="3" class="form-control"
                        placeholder="Brief about the doctor, expertise, achievements…">{{ old('bio', $doctor?->bio) }}</textarea>
            </div>

            @if($doctor)
            <div class="col-12">
              <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $doctor->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
              </label>
            </div>
            @endif

          </div>
        </div>
      </div>
    </div>

    {{-- Photo sidebar --}}
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Photo</h3></div>
        <div class="card-body text-center">
          @if($doctor?->photo)
            <img id="photoPreview" src="{{ asset('storage/'.$doctor->photo) }}"
                 class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">
          @else
            <div class="avatar avatar-xl rounded-circle mb-3"
                 style="background:#e1f5ee;color:#0f6e56;font-size:1.5rem;margin:auto;">
              Dr
            </div>
          @endif
          <div>
            <label class="btn btn-outline-secondary btn-sm" for="photoInput">
              <i class="ti ti-upload me-1"></i> Upload Photo
            </label>
            <input type="file" name="photo" id="photoInput" accept="image/*"
                   class="d-none" onchange="previewPhoto(event)">
            <div class="text-muted small mt-2">JPG/PNG, max 2MB</div>
          </div>
        </div>
      </div>

      @if($doctor)
      <div class="card mt-3">
        <div class="card-body">
          <a href="{{ route('admin.slots.index', $doctor) }}" class="btn btn-outline-primary w-100">
            <i class="ti ti-calendar me-1"></i> Manage Slots
          </a>
        </div>
      </div>
      @endif

      <div class="mt-3 d-grid gap-2">
        <button type="submit" class="btn" style="background:#0f6e56;color:#fff;">
          <i class="ti ti-device-floppy me-1"></i>
          {{ $doctor ? 'Update Doctor' : 'Add Doctor' }}
        </button>
        <a href="{{ route('admin.doctors.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
// AJAX: Load departments when hospital changes
const hospitalSel = document.getElementById('hospitalSelect');
const deptSel     = document.getElementById('deptSelect');
const currentDept = "{{ old('department_id', $doctor?->department_id) }}";

if (hospitalSel && !hospitalSel.disabled) {
  hospitalSel.addEventListener('change', function () {
    const hid = this.value;
    deptSel.innerHTML = '<option value="">Loading…</option>';
    if (!hid) { deptSel.innerHTML = '<option value="">— Select Department —</option>'; return; }
    fetch(`/admin/hospitals/${hid}/departments`)
      .then(r => r.json())
      .then(data => {
        deptSel.innerHTML = '<option value="">— Select Department —</option>';
        data.forEach(d => {
          deptSel.innerHTML += `<option value="${d.id}">${d.name}</option>`;
        });
      });
  });
}

// Photo preview
function previewPhoto(e) {
  const r = new FileReader();
  r.onload = ev => {
    const el = document.getElementById('photoPreview') || document.querySelector('.avatar-xl');
    el.outerHTML = `<img id="photoPreview" src="${ev.target.result}"
      class="rounded-circle mb-3" style="width:100px;height:100px;object-fit:cover;">`;
  };
  r.readAsDataURL(e.target.files[0]);
}
</script>
@endpush
