{{-- ══════════════════════════════════════════════
     departments/index.blade.php
══════════════════════════════════════════════ --}}
@extends('admin.layouts.app')
@section('title','Departments')
@section('page-title','Departments')

@section('page-actions')
  <a href="{{ route('admin.departments.create') }}" class="btn btn-sm"
     style="background:#0f6e56;color:#fff;">
    <i class="ti ti-plus me-1"></i> Add Department
  </a>
@endsection

@section('content')
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex gap-2">
      @if(auth('admin')->user()->isSuperAdmin())
        <select name="hospital_id" class="form-select form-select-sm" style="max-width:220px;"
                onchange="this.form.submit()">
          <option value="">All Hospitals</option>
          @foreach($hospitals as $id => $name)
            <option value="{{ $id }}" {{ request('hospital_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
      @endif
      <a href="{{ route('admin.departments.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Department</th>
          @if(auth('admin')->user()->isSuperAdmin())<th>Hospital</th>@endif
          <th>Doctors</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($departments as $i => $dept)
        <tr>
          <td class="text-muted small">{{ $departments->firstItem() + $i }}</td>
          <td>
            <div class="fw-semibold">{{ $dept->name }}</div>
            @if($dept->description)
              <div class="text-muted small">{{ Str::limit($dept->description, 60) }}</div>
            @endif
          </td>
          @if(auth('admin')->user()->isSuperAdmin())
          <td class="text-muted small">{{ $dept->hospital->name }}</td>
          @endif
          <td>
            <a href="{{ route('admin.doctors.index', ['department_id' => $dept->id]) }}"
               class="text-decoration-none">
              {{ $dept->doctors_count }} doctor{{ $dept->doctors_count == 1 ? '' : 's' }}
            </a>
          </td>
          <td>
            <span class="badge {{ $dept->is_active ? 'bg-success' : 'bg-secondary' }}">
              {{ $dept->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.departments.edit', $dept) }}"
                 class="btn btn-sm btn-outline-secondary"><i class="ti ti-edit"></i></a>
              <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}"
                    onsubmit="return confirm('Delete {{ $dept->name }}?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger"
                        {{ $dept->doctors_count > 0 ? 'disabled title=Remove doctors first' : '' }}>
                  <i class="ti ti-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted py-5">
            No departments found. <a href="{{ route('admin.departments.create') }}">Add one</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($departments->hasPages())
  <div class="card-footer d-flex justify-content-end">{{ $departments->links() }}</div>
  @endif
</div>
@endsection
