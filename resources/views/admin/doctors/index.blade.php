@extends('admin.layouts.app')
@section('title','Doctors')
@section('page-title','Doctors')

@section('page-actions')
  <a href="{{ route('admin.doctors.create') }}" class="btn btn-sm"
     style="background:#0f6e56;color:#fff;">
    <i class="ti ti-plus me-1"></i> Add Doctor
  </a>
@endsection

@section('content')
<div class="card mb-3">
  <div class="card-body py-2">
    <form method="GET" class="d-flex flex-wrap gap-2 align-items-center">
      @if(auth('admin')->user()->isSuperAdmin())
        <select name="hospital_id" class="form-select form-select-sm" style="max-width:200px;"
                onchange="this.form.submit()">
          <option value="">All Hospitals</option>
          @foreach($hospitals as $id => $name)
            <option value="{{ $id }}" {{ request('hospital_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
          @endforeach
        </select>
      @endif
      <input type="text" name="search" value="{{ request('search') }}"
             class="form-control form-control-sm" placeholder="Search doctor…" style="max-width:200px;">
      <button class="btn btn-sm btn-outline-secondary">Search</button>
      <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-danger">Reset</a>
    </form>
  </div>
</div>

<div class="card">
  <div class="table-responsive">
    <table class="table table-vcenter card-table table-hover">
      <thead>
        <tr>
          <th>#</th>
          <th>Doctor</th>
          <th>Department</th>
          @if(auth('admin')->user()->isSuperAdmin())<th>Hospital</th>@endif
          <th>Exp.</th>
          <th>Fee (₹)</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($doctors as $i => $doctor)
        <tr>
          <td class="text-muted small">{{ $doctors->firstItem() + $i }}</td>
          <td>
            <div class="d-flex align-items-center gap-2">
              @if($doctor->photo)
                <img src="{{ asset('storage/'.$doctor->photo) }}"
                     class="rounded-circle" style="width:38px;height:38px;object-fit:cover;">
              @else
                <span class="avatar avatar-sm rounded-circle"
                      style="background:#e1f5ee;color:#0f6e56;font-size:.8rem;">
                  Dr
                </span>
              @endif
              <div>
                <div class="fw-semibold">{{ $doctor->name }}</div>
                <div class="text-muted small">{{ $doctor->qualification }}</div>
              </div>
            </div>
          </td>
          <td>{{ $doctor->department->name }}</td>
          @if(auth('admin')->user()->isSuperAdmin())
          <td class="text-muted small">{{ $doctor->hospital->name }}</td>
          @endif
          <td>{{ $doctor->experience_years }} yr{{ $doctor->experience_years == 1 ? '' : 's' }}</td>
          <td>₹ {{ number_format($doctor->consultation_fee) }}</td>
          <td>
            <span class="badge {{ $doctor->is_active ? 'bg-success' : 'bg-secondary' }}">
              {{ $doctor->is_active ? 'Active' : 'Inactive' }}
            </span>
          </td>
          <td>
            <div class="d-flex gap-1">
              <a href="{{ route('admin.slots.index', $doctor) }}"
                 class="btn btn-sm btn-outline-primary" title="Manage Slots">
                <i class="ti ti-calendar"></i>
              </a>
              <a href="{{ route('admin.doctors.edit', $doctor) }}"
                 class="btn btn-sm btn-outline-secondary">
                <i class="ti ti-edit"></i>
              </a>
              <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}"
                    onsubmit="return confirm('Delete Dr. {{ $doctor->name }}?')">
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
            No doctors found. <a href="{{ route('admin.doctors.create') }}">Add one</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($doctors->hasPages())
  <div class="card-footer d-flex justify-content-end">{{ $doctors->links() }}</div>
  @endif
</div>
@endsection
