@extends('layouts.master')

@section('title', 'Accesso Negato')

@section('body')
<div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100">
        <div class="col-md-8 offset-md-2 col-lg-6 offset-lg-3">
            <div class="card border-danger shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Accesso non autorizzato</h5>
                </div>
                <div class="card-body text-center">
                    <p class="mb-3">Qualcosa è andato storto mentre tentavi di accedere a questa pagina.</p>
                    @if(isset($message))
                        <div class="alert alert-warning" role="alert">
                            {{ $message }}
                        </div>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-danger">
                        <i class="bi bi-arrow-left-circle me-1"></i> Torna alla Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
