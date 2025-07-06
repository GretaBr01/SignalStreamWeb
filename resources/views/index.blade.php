@extends('layouts.master') <!-- title - active_home - active_MyLibrary - breadcrumb - body -->

@section('title', __('messages.title'))

@section('active_home','active')

@section('body')
  <div class="background-home text-center bg-light d-flex flex-column justify-content-center align-items-center">
    <h1 class="display-1 fw-bold text-purple">{{ __('messages.title') }}</h1>
    <p class="fs-3 lead text-muted">{{ __('messages.subtitle') }}</p>
    <div class="d-flex justify-content-center gap-3 mt-4">
      @if(!auth()->check())
      <a href="{{ route('login') }}" class="btn btn-primary px-4">{{ __('messages.btn_login') }}</a>
      @endif
      <a href="#info" class="btn btn-outline-secondary px-4">{{ __('messages.btn_more') }}</a>
    </div>
  </div>

  <div id="info" class="container py-5">
    <h2 class="mb-4 text-center">{{ __('messages.how_it_works') }}</h2>
    <div class="row text-center">
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-cpu fs-2 text-purple"></i>
            <h5 class="mt-3">{{ __('messages.step_1_title') }}</h5>
            <p class="text-muted">{{ __('messages.step_1_desc') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-wifi fs-2 text-purple"></i>
            <h5 class="mt-3">{{ __('messages.step_2_title') }}</h5>
            <p class="text-muted">{{ __('messages.step_2_desc') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-graph-up fs-2 text-purple"></i>
            <h5 class="mt-3">{{ __('messages.step_3_title') }}</h5>
            <p class="text-muted">{{ __('messages.step_3_desc') }}</p>
          </div>
        </div>
      </div>
      <div class="col-md-3 mb-4">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <i class="bi bi-archive fs-2 text-purple"></i>
            <h5 class="mt-3">{{ __('messages.step_4_title') }}</h5>
            <p class="text-muted">{{ __('messages.step_4_desc') }}</p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="bg-purple text-white py-5 text-center">
    <h2 class="mb-3">{{ __('messages.get_started_title') }}</h2>
    @if(!auth()->check())
    <p class="lead">{{ __('messages.get_started_desc') }}</p>
    <a href="{{ route('register') }}" class="btn btn-light text-purple px-4 mt-3">{{ __('messages.register_now') }}</a>
    @endif
  </div>

@endsection