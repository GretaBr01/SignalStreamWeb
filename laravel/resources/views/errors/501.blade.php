@extends('layouts.master')

@section('body')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card border-danger shadow">
                <div class="card-header bg-danger text-white">
                    <b>Not implemented:</b> this functionality has not been implemented yet
                </div>
                <div class="card-body text-center">
                    <h1 class="text-purple fw-bold" style="font-size: 3rem; font-family: 'Courier New', monospace;">
                        Coming Soon...
                    </h1>
                    <p class="mt-4">
                        <a class="btn btn-danger" href="{{ url()->previous() }}">
                            <i class="bi bi-box-arrow-left"></i> Go Back!
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
