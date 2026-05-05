<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.forgot_title') }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .auth-wrapper { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f1f5f9; }
        .auth-card { background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,.1); width: 100%; max-width: 420px; }
        .auth-card h2 { font-size: 1.5rem; color: #1e293b; margin-bottom: .5rem; }
        .auth-card p.subtitle { color: #6b7280; font-size: .9rem; margin-bottom: 1.5rem; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: .85rem; font-weight: 600; color: #374151; margin-bottom: .4rem; }
        .form-group input { width: 100%; padding: .6rem .85rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: .95rem; box-sizing: border-box; }
        .form-group input:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,.15); }
        .error { color: #dc2626; font-size: .8rem; display: block; margin-top: .3rem; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; font-size: .9rem; }
        .submit-btn { width: 100%; background: #2563eb; color: #fff; border: none; padding: .75rem; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; margin-top: .5rem; }
        .submit-btn:hover { background: #1d4ed8; }
        .back-link { display: block; text-align: center; margin-top: 1rem; color: #2563eb; font-size: .9rem; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>{{ __('messages.forgot_title') }}</h2>
        <p class="subtitle">{{ __('messages.forgot_subtitle') }}</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">{{ __('messages.login_email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="you@example.com">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="submit-btn">{{ __('messages.forgot_btn') }}</button>
        </form>

        <a href="{{ route('login') }}" class="back-link">{{ __('messages.forgot_back') }}</a>
    </div>
</div>
</body>
</html>
