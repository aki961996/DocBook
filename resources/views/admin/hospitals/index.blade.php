@extends('admin.layouts.app')

@section('title', 'Hospitals')
@section('page-title', 'Hospitals')

@section('page-actions')
  <a href="{{ route('admin.hospitals.create') }}" class="btn btn-sm"
     style="background:#0f6e56;color:#fff;">
    <i class="ti ti-plus me-1"></i> Add Hospital
  </a>
@endsection

@section('content')

{{-- Search --}}
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2 align-items-center">
      <input type="text" name="search" value="{{ request('search') }}"
             class="form-control form-control-sm" placeholder="Search by name or city…" style="max-width:280px;">
      <button class="btn btn-sm btn-outline-secondary">Search</button>
      @if(request('search'))
        <a href="{{ route('admin.hospitals.index') }}" class="btn btn-sm btn-outline-danger">Clear</a>
      @endif
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Hospital</th>
          <th>City</th>
          <th>Phone</th>
          <th>Doctors</th>
          <th>Depts</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($hospitals as $i => $hospital)
        <tr>
          <td class="text-muted small">{{ $hospitals->firstItem() + $i }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              @if($hospital->logo)
                <img src="{{ asset('storage/'.$hospital->logo) }}"
                     class="rounded" style="width:36px;height:36px;object-fit:cover;">
              @else
                <span class="avatar avatar-sm" style="background:#e1f5ee;color:#0f6e56;font-size:.8rem;">
                  {{ strtoupper(substr($hospital->name,0,2)) }}
                </span>
              @endif
              <div>
                <div class="fw-semibold">{{ $hospital->name }}</div>
                <div class="text-muted small">{{ $hospital->email }}</div>
              </div>
            </div>
          </td>
          <td>{{ $hospital->city }}, {{ $hospital->state }}</td>
          <td>{{ $hospital->phone }}</td>
          <td>
            <a href="{{ route('admin.doctors.index', ['hospital_id' => $hospital->id]) }}"
               class="text-decoration-none">{{ $hospital->doctors_count }}</a>
          </td>
          <td>{{ $hospital->departments_count }}</td>
          <td>
            <div class="form-check form-switch" style="margin:0;">
              <input class="form-check-input toggle-status" type="checkbox"
                     data-url="{{ route('admin.hospitals.toggle-status', $hospital) }}"
                     {{ $hospital->is_active ? 'checked' : '' }}>
            </div>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.hospitals.edit', $hospital) }}"
                 class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-edit"></i>
              </a>
              <form method="POST" action="{{ route('admin.hospitals.destroy', $hospital) }}"
                    onsubmit="return confirm('Delete {{ $hospital->name }}? This cannot be undone.')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">
                  <i class="ti ti-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8" class="text-center text-muted py-5">
            <i class="ti ti-building-hospital" style="font-size:2rem;opacity:.3;"></i>
            <p class="mt-2">No hospitals found. <a href="{{ route('admin.hospitals.create') }}">Add one</a></p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($hospitals->hasPages())
  <div class="card-footer d-flex justify-content-end">
    {{ $hospitals->links() }}
  </div>
  @endif
</div>
@endsection

@push('scripts')
<script>
// Toggle active status via AJAX
document.querySelectorAll('.toggle-status').forEach(el => {
  el.addEventListener('change', function () {
    fetch(this.dataset.url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
      }
    })
    .then(r => r.json())
    .then(d => {
      this.checked = d.status;
    })
    .catch(() => this.checked = !this.checked);
  });
});
</script>
@endpush
