{{-- doctors/create.blade.php --}}
@extends('admin.layouts.app')
@section('title','Add Doctor')
@section('page-title','Add Doctor')
@section('page-actions')
  <a href="{{ route('admin.doctors.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection
@section('content')
@include('admin.doctors._form',['doctor'=>null,'action'=>route('admin.doctors.store'),'method'=>'POST'])
@endsection
