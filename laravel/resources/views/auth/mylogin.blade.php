@extends('layouts.master')

@section('title', __('messages.btn_login') . ' - ' . __('messages.title'))

@section('body')
<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold text-purple mt-3">{{ __('messages.btn_login') }}</h2>
            <p class="text-muted mt-3">{{ __('messages.login_subtitle') }}</p>
        </div>

        @if (session('error'))
            <div class="alert alert-danger text-center">
                {{ session('error') }}
            </div>
        @endif

        <form id="login-form" method="POST" action="{{ route('login') }}" >
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('messages.placeholder_email') }}" required autofocus>
                <div class="invalid-feedback d-block" id="invalid-email"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                <div class="invalid-feedback d-block" id="invalid-password"></div>
            </div>

            {{-- <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label" for="remember">Ricordami</label>
            </div>
            <a href="{{ route('password.request') }}" class="small text-decoration-none">Password dimenticata?</a>
            </div> --}}
            
            <div class="form-group text-center mb-3">
                <label for="login-submit" class="btn btn-primary w-100"><i class="bi bi-door-open"></i> {{ __('messages.btn_login') }}</label>
                <input id="login-submit" class="d-none" type="submit" value="{{ __('messages.btn_login') }}">
            </div>
        </form>

        <div class="text-center mt-4">
            <p class="small">{{ __('messages.no_account') }} <a href="{{ route('register') }}">{{ __('messages.register') }}</a></p>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        $("#login-form").submit(function(event){
            let email = $("input[name='email']").val().trim();
            let password = $("input[name='password']").val().trim();
            let valid = true;

            if (email === "") {
                $("#invalid-email").text("{{ __('messages.required_email') }}");
                valid = false;
            } else {
                $("#invalid-email").text("");
            }

            if (password === "") {
                $("#invalid-password").text("{{ __('messages.required_password') }}");
                valid = false;
            } else {
                $("#invalid-password").text("");
            }

            if (!valid) event.preventDefault();
        });

    });
</script>
@endsection
