@extends('admin.layouts.app')
@section('title','Edit Department')
@section('page-title','Edit: '.$department->name)
@section('page-actions')
  <a href="{{ route('admin.departments.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection
@section('content')
@include('admin.departments._form',[
  'dept'   => $department,
  'action' => route('admin.departments.update', $department),
  'method' => 'PUT',
])
@endsection
