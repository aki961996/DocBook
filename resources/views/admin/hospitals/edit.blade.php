@extends('admin.layouts.app')
@section('title','Edit Hospital')
@section('page-title','Edit: ' . $hospital->name)

@section('page-actions')
  <a href="{{ route('admin.hospitals.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection

@section('content')
@include('admin.hospitals._form', [
  'hospital' => $hospital,
  'action'   => route('admin.hospitals.update', $hospital),
  'method'   => 'PUT'
])
@endsection
