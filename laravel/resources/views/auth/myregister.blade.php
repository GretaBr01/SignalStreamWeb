@extends('layouts.master')

@section('title', __('messages.register') . ' - ' . __('messages.title'))

@section('body')

<div class="d-flex align-items-center justify-content-center min-vh-100 bg-light">
    <div class="card shadow-lg p-4" style="max-width: 420px; width: 100%; border-radius: 1rem;">
        <div class="text-center mb-4">
            <h2 class="display-6 fw-bold text-purple">{{ __('messages.register') }}</h2>
            <p class="text-muted">{{ __('messages.register_title') }} <strong>{{ __('messages.title')}}</strong></p>
        </div>

        <form id="register-form" method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">{{ __('messages.name') }}</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="{{ __('messages.placeholder_name') }}" required>
                <div class="invalid-feedback d-block" id="invalid-name"></div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('messages.email') }}</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="{{ __('messages.placeholder_email') }}" required>
                <div class="invalid-feedback d-block" id="invalid-email"></div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('messages.password') }}</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="{{ __('messages.placeholder_password') }}" required>
                <div class="invalid-feedback d-block" id="invalid-password"></div>
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('messages.password_confirm') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="{{ __('messages.placeholder_confirm') }}" required>
                <div class="invalid-feedback d-block" id="invalid-confirm"></div>
            </div>

            <div class="form-group text-center mt-4">
                <label for="register-submit" class="btn btn-success w-100">
                    <i class="bi bi-person-plus"></i> {{ __('messages.register') }}
                </label>
                <input id="register-submit" class="d-none" type="submit" value="{{ __('messages.register') }}">
            </div>
        </form>

        <div class="text-center mt-3">
            <small>{{ __('messages.already_account') }} <a href="{{ route('login') }}" class="text-decoration-none">{{ __('messages.btn_login') }}</a></small>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $("#register-form").submit(function(event){
            let name = $("input[name='name']").val().trim();
            let email = $("input[name='email']").val().trim();
            let password = $("input[name='password']").val().trim();
            let confirmPassword = $("input[name='password_confirmation']").val().trim();
            let passwordRegex = /^(?=.*[0-9])(?=.*[!-\*\[\]\$&\/]).{8,}$/;
            let valid = true;

            $(".invalid-feedback").text(""); // Pulisci errori precedenti

            if (name === "") {
                $("#invalid-name").text("{{ __('messages.name_required') }}");
                valid = false;
            }

            if (email === "") {
                $("#invalid-email").text("{{ __('messages.email_required') }}");
                valid = false;
            }

            if (!passwordRegex.test(password)) {
                $("#invalid-password").text("{{ __('messages.password_invalid') }}");
                valid = false;
            }

            if (confirmPassword !== password) {
                $("#invalid-confirm").text("{{ __('messages.password_mismatch') }}");
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                return;
            }


        });
    });
</script>
@endsection
