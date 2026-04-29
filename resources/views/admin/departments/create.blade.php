{{-- departments/create.blade.php --}}
@extends('admin.layouts.app')
@section('title','Add Department')
@section('page-title','Add Department')
@section('page-actions')
  <a href="{{ route('admin.departments.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection
@section('content')
@include('admin.departments._form',['dept'=>null,'action'=>route('admin.departments.store'),'method'=>'POST'])
@endsection
