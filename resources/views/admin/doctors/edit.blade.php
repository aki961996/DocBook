@extends('admin.layouts.app')
@section('title','Edit Doctor')
@section('page-title','Edit: '.$doctor->name)
@section('page-actions')
  <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection
@section('content')
@include('admin.doctors._form',[
  'doctor' => $doctor,
  'action' => route('admin.doctors.update', $doctor),
  'method' => 'PUT',
])
@endsection
