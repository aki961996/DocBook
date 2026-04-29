{{-- resources/views/admin/hospitals/_form.blade.php --}}
<form method="POST" action="{{ $action }}" enctype="multipart/form-data">
  @csrf
  @if($method !== 'POST') @method($method) @endif

  <div class="row">
    <div class="col-lg-8">

      <div class="card">
        <div class="card-header"><h3 class="card-title">Hospital Details</h3></div>
        <div class="card-body">

          <div class="row g-3">
            <div class="col-12">
              <label class="form-label required">Hospital Name</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name', $hospital?->name) }}" placeholder="e.g. City General Hospital">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label required">Address</label>
              <textarea name="address" rows="2"
                        class="form-control @error('address') is-invalid @enderror"
                        placeholder="Full street address">{{ old('address', $hospital?->address) }}</textarea>
              @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">City</label>
              <input type="text" name="city" class="form-control @error('city') is-invalid @enderror"
                     value="{{ old('city', $hospital?->city) }}" placeholder="Palakkad">
              @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">State</label>
              <input type="text" name="state" class="form-control @error('state') is-invalid @enderror"
                     value="{{ old('state', $hospital?->state) }}" placeholder="Kerala">
              @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
              <label class="form-label required">Pincode</label>
              <input type="text" name="pincode" class="form-control @error('pincode') is-invalid @enderror"
                     value="{{ old('pincode', $hospital?->pincode) }}" placeholder="678001">
              @error('pincode')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label required">Phone</label>
              <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                     value="{{ old('phone', $hospital?->phone) }}" placeholder="+91 9876543210">
              @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                     value="{{ old('email', $hospital?->email) }}" placeholder="info@hospital.com">
              @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" rows="3" class="form-control"
                        placeholder="Brief about the hospital…">{{ old('description', $hospital?->description) }}</textarea>
            </div>

            @if($hospital)
            <div class="col-12">
              <label class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                       {{ old('is_active', $hospital->is_active) ? 'checked' : '' }}>
                <span class="form-check-label">Active (visible on web portal)</span>
              </label>
            </div>
            @endif
          </div>
        </div>
      </div>

    </div>

    {{-- Sidebar: logo upload --}}
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header"><h3 class="card-title">Logo</h3></div>
        <div class="card-body text-center">
          @if($hospital?->logo)
            <img id="logoPreview" src="{{ asset('storage/'.$hospital->logo) }}"
                 class="rounded mb-3" style="max-height:100px;max-width:100%;">
          @else
            <div id="logoPreview" class="avatar avatar-xl mb-3"
                 style="background:#e1f5ee;color:#0f6e56;font-size:1.5rem;margin:auto;">
              🏥
            </div>
          @endif
          <div>
            <label class="btn btn-outline-secondary btn-sm" for="logoInput">
              <i class="ti ti-upload me-1"></i> Upload Logo
            </label>
            <input type="file" name="logo" id="logoInput" accept="image/*"
                   class="d-none" onchange="previewLogo(event)">
            <div class="text-muted small mt-2">JPG/PNG, max 2MB</div>
          </div>
        </div>
      </div>

      <div class="mt-3 d-grid gap-2">
        <button type="submit" class="btn" style="background:#0f6e56;color:#fff;">
          <i class="ti ti-device-floppy me-1"></i>
          {{ $hospital ? 'Update Hospital' : 'Create Hospital' }}
        </button>
        <a href="{{ route('admin.hospitals.index') }}" class="btn btn-outline-secondary">Cancel</a>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
function previewLogo(e) {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = function(ev) {
    const prev = document.getElementById('logoPreview');
    prev.outerHTML = `<img id="logoPreview" src="${ev.target.result}"
      class="rounded mb-3" style="max-height:100px;max-width:100%;">`;
  };
  reader.readAsDataURL(file);
}
</script>
@endpush
