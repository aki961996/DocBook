{{-- admin/departments/_form.blade.php --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div class="row">
    <div class="col-lg-7">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Department Info</h3></div>
        <div class="card-body">
          <div class="row g-3">

            <div class="col-12">
              <label class="form-label required">Hospital</label>
              <select name="hospital_id"
                      class="form-select @error('hospital_id') is-invalid @enderror"
                      {{ $dept ? 'disabled' : '' }}>
                <option value="">— Select Hospital —</option>
                @foreach($hospitals as $id => $name)
                  <option value="{{ $id }}"
                          {{ old('hospital_id', $dept?->hospital_id) == $id ? 'selected' : '' }}>
                    {{ $name }}
                  </option>
                @endforeach
              </select>
              {{-- Keep value on form submit when disabled --}}
              @if($dept)
                <input type="hidden" name="hospital_id" value="{{ $dept->hospital_id }}">
              @endif
              @error('hospital_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label required">Department Name</label>
              <input type="text" name="name"
                     class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $dept?->name) }}"
                     placeholder="e.g. Gynaecology & Obstetrics">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" rows="3" class="form-control"
                        placeholder="Brief description of this department…">{{ old('description', $dept?->description) }}</textarea>
            </div>

            @if($dept)
            <div class="col-12">
              <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $dept->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active</span>
              </label>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Icon (optional)</h3></div>
        <div class="card-body text-center">
          @if($dept?->icon)
            <img id="iconPreview" src="{{ asset('storage/'.$dept->icon) }}"
                 class="rounded mb-3" style="max-height:80px;">
          @else
            <div class="avatar avatar-lg mb-3" style="background:#e1f5ee;color:#0f6e56;font-size:1.4rem;margin:auto;">
              🏥
            </div>
          @endif
          <div>
            <label class="btn btn-outline-secondary btn-sm" for="iconInput">
              <i class="ti ti-upload me-1"></i> Upload Icon
            </label>
            <input type="file" name="icon" id="iconInput" accept="image/*"
                   class="d-none" onchange="previewIcon(event)">
            <div class="text-muted small mt-1">PNG/SVG, max 1MB</div>
          </div>
        </div>
      </div>

      <div class="mt-3 d-grid gap-2">
        <button type="submit" class="btn" style="background:#0f6e56;color:#fff;">
          <i class="ti ti-device-floppy me-1"></i>
          {{ $dept ? 'Update Department' : 'Create Department' }}
        </button>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
function previewIcon(e) {
  const file = e.target.files[0];
  if (!file) return;
  const r = new FileReader();
  r.onload = ev => {
    const el = document.getElementById('iconPreview') || document.querySelector('.avatar-lg');
    el.outerHTML = `<img id="iconPreview" src="${ev.target.result}" class="rounded mb-3" style="max-height:80px;">`;
  };
  r.readAsDataURL(file);
}
</script>
@endpush
