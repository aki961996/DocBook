{{-- ══════════════════════════════════════════════
     hospitals/create.blade.php
══════════════════════════════════════════════ --}}
@extends('admin.layouts.app')
@section('title','Add Hospital')
@section('page-title','Add Hospital')

@section('page-actions')
  <a href="{{ route('admin.hospitals.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="ti ti-arrow-left me-1"></i> Back
  </a>
@endsection

@section('content')
@include('admin.hospitals._form', ['hospital' => null, 'action' => route('admin.hospitals.store'), 'method' => 'POST'])
@endsection
