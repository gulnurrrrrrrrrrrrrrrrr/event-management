<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.reset_title') }} | EventMaster</title>
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
        .submit-btn { width: 100%; background: #2563eb; color: #fff; border: none; padding: .75rem; border-radius: 6px; font-size: 1rem; font-weight: 600; cursor: pointer; }
        .submit-btn:hover { background: #1d4ed8; }
        .hint { font-size: .78rem; color: #9ca3af; margin-top: .3rem; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <h2>{{ __('messages.reset_title') }}</h2>
        <p class="subtitle">{{ __('messages.reset_subtitle') }}</p>

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">{{ __('messages.login_email') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', $email) }}" required readonly>
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password">{{ __('messages.reset_title') }}</label>
                <input type="password" id="password" name="password" required autocomplete="new-password">
                <span class="hint">{{ __('messages.password_hint') }}</span>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">{{ __('messages.field_password_confirm') }}</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit" class="submit-btn">{{ __('messages.reset_btn') }}</button>
        </form>
    </div>
</div>
</body>
</html>
