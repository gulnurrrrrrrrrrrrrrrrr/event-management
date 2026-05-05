<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.register_title') }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .auth-wrapper { min-height:100vh; display:flex; align-items:center; justify-content:center; background:#f1f5f9; padding: 2rem 1rem; }
        .auth-card { background:#fff; padding:2.5rem; border-radius:12px; box-shadow:0 4px 16px rgba(0,0,0,.1); width:100%; max-width:480px; }
        .auth-card h2 { font-size:1.6rem; color:#1e293b; margin-bottom:.25rem; }
        .subtitle { color:#6b7280; font-size:.9rem; margin-bottom:1.75rem; }
        .form-group { margin-bottom:1.2rem; }
        .form-group label { display:block; font-size:.85rem; font-weight:600; color:#374151; margin-bottom:.4rem; }
        .form-group input,
        .form-group select { width:100%; padding:.65rem .9rem; border:1px solid #cbd5e1; border-radius:6px; font-size:.95rem; box-sizing:border-box; background:#fff; }
        .form-group input:focus,
        .form-group select:focus { outline:none; border-color:#2563eb; box-shadow:0 0 0 3px rgba(37,99,235,.15); }
        .radio-group { display:flex; gap:1.5rem; padding:.4rem 0; }
        .radio-group label { display:flex; align-items:center; gap:.4rem; font-size:.9rem; font-weight:400; color:#374151; cursor:pointer; }
        .radio-group input[type=radio] { accent-color:#2563eb; width:16px; height:16px; }
        .error { color:#dc2626; font-size:.8rem; display:block; margin-top:.3rem; }
        .alert-success { background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem; }
        .alert-error   { background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem; }
        .submit-btn { width:100%; background:#2563eb; color:#fff; border:none; padding:.75rem; border-radius:6px; font-size:1rem; font-weight:600; cursor:pointer; margin-top:.5rem; }
        .submit-btn:hover { background:#1d4ed8; }
        .divider { text-align:center; color:#9ca3af; margin:1.25rem 0; font-size:.85rem; }
        .login-link { display:block; text-align:center; color:#2563eb; font-size:.9rem; text-decoration:none; }
        .login-link:hover { text-decoration:underline; }
        .logo { font-size:1.2rem; font-weight:800; color:#2563eb; margin-bottom:1.5rem; display:block; }
        .lang-switcher { display:flex; justify-content:center; gap:8px; margin-bottom:1.5rem; }
        .lang-btn { padding:4px 12px; border-radius:5px; font-size:.8rem; font-weight:600; text-decoration:none; border:1px solid #cbd5e1; color:#374151; }
        .lang-btn.active { background:#2563eb; color:#fff; border-color:#2563eb; }
        .lang-btn:hover { background:#f1f5f9; }
        .lang-btn.active:hover { background:#1d4ed8; }
        .file-input-wrapper { position:relative; }
        .file-input-wrapper input[type=file] { width:100%; padding:.55rem .9rem; border:1px solid #cbd5e1; border-radius:6px; font-size:.9rem; box-sizing:border-box; background:#fff; cursor:pointer; }
        .hint { font-size:.78rem; color:#9ca3af; margin-top:.3rem; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">

        {{-- Переключатель языка --}}
        <div class="lang-switcher">
            <a href="{{ route('lang.switch', 'ru') }}"
               class="lang-btn {{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
            <a href="{{ route('lang.switch', 'en') }}"
               class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
            <a href="{{ route('lang.switch', 'kk') }}"
               class="lang-btn {{ app()->getLocale() === 'kk' ? 'active' : '' }}">KZ</a>
        </div>

        <span class="logo">⚡ EventMaster</span>
        <h2>{{ __('messages.register_title') }}</h2>
        <p class="subtitle">{{ __('messages.register_subtitle') }}</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert-error">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.field_name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="{{ __('messages.field_name') }}">
                @error('name') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.login_email') }}</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                @error('email') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.login_password') }}</label>
                <input type="password" name="password" required placeholder="••••••••">
                <span class="hint">{{ __('messages.password_hint') }}</span>
                @error('password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_password_confirm') }}</label>
                <input type="password" name="password_confirmation" required placeholder="••••••••">
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_birthdate') }}</label>
                <input type="date" name="birthdate" value="{{ old('birthdate') }}" required>
                @error('birthdate') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_gender') }}</label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required>
                        {{ __('messages.field_gender_female') }}
                    </label>
                    <label>
                        <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                        {{ __('messages.field_gender_male') }}
                    </label>
                </div>
                @error('gender') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_city_reg') }}</label>
                <select name="city" required>
                    <option value="">{{ __('messages.field_choose_category') }}</option>
                    <option value="almaty"   {{ old('city') == 'almaty'   ? 'selected' : '' }}>Алматы</option>
                    <option value="astana"   {{ old('city') == 'astana'   ? 'selected' : '' }}>Астана</option>
                    <option value="shymkent" {{ old('city') == 'shymkent' ? 'selected' : '' }}>Шымкент</option>
                </select>
                @error('city') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_images') }}</label>
                <div class="file-input-wrapper">
                    <input type="file" name="avatar" accept="image/*">
                </div>
            </div>

            <button type="submit" class="submit-btn">{{ __('messages.register_btn') }}</button>
        </form>

        <div class="divider">or</div>
        <a href="{{ route('login') }}" class="login-link">{{ __('messages.register_has_account') }}</a>

    </div>
</div>
</body>
</html>
