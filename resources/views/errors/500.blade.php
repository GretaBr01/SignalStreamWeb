@extends('layouts.master')

@section('body')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card border-danger shadow">
                <div class="card-header bg-danger text-white">
                    <b>Illegal page access:</b> something <strong>wrong</strong> happened while accessing this page!
                </div>
                <div class="card-body text-center">
                    <p>Error 500 - Internal server error. Please contact the administrator.</p>
                    <p><a class="btn btn-danger" href="{{ route('home') }}"><i class="bi bi-box-arrow-left"></i> Back to home!</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection