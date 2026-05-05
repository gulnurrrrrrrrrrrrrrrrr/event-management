<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.admin_categories') }} | EventMaster Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.admin-navbar')

<main style="padding:2rem; max-width:900px; margin:0 auto;">

    <h1 style="font-size:1.8rem; margin-bottom:1.5rem; color:#1e293b;">{{ __('messages.admin_categories') }}</h1>

    @if(session('success'))
        <div style="background:#d1fae5;color:#065f46;border:1px solid #6ee7b7;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;">
            {{ session('error') }}
        </div>
    @endif

    {{-- Форма добавления категории --}}
    <div style="background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.08);padding:1.5rem;margin-bottom:2rem;">
        <h2 style="font-size:1.1rem;margin-bottom:1rem;color:#1e293b;">+ {{ __('messages.admin_categories') }}</h2>
        <form action="{{ route('admin.categories.store') }}" method="POST" style="display:flex;gap:.75rem;align-items:flex-start;">
            @csrf
            <div style="flex:1;">
                <input type="text" name="name" placeholder="{{ __('messages.admin_categories') }}"
                       value="{{ old('name') }}"
                       style="width:100%;padding:.6rem .85rem;border:1px solid #cbd5e1;border-radius:6px;font-size:.95rem;box-sizing:border-box;">
                @error('name')
                    <span style="color:#dc2626;font-size:.8rem;">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit"
                    style="background:#2563eb;color:#fff;border:none;padding:.65rem 1.25rem;border-radius:6px;font-size:.9rem;font-weight:600;cursor:pointer;white-space:nowrap;">
                {{ __('messages.admin_save') }}
            </button>
        </form>
    </div>

    {{-- Таблица категорий --}}
    <div style="background:#fff;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.08);overflow:hidden;">
        <table style="width:100%;border-collapse:collapse;">
            <thead>
                <tr>
                    <th style="background:#1e293b;color:#fff;padding:.75rem 1rem;text-align:left;font-size:.85rem;">ID</th>
                    <th style="background:#1e293b;color:#fff;padding:.75rem 1rem;text-align:left;font-size:.85rem;">{{ __('messages.admin_user_name') }}</th>
                    <th style="background:#1e293b;color:#fff;padding:.75rem 1rem;text-align:left;font-size:.85rem;">{{ __('messages.admin_events') }}</th>
                    <th style="background:#1e293b;color:#fff;padding:.75rem 1rem;text-align:left;font-size:.85rem;">{{ __('messages.admin_actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td style="padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;color:#9ca3af;font-size:.85rem;">{{ $category->id }}</td>
                    <td style="padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;font-weight:600;color:#1e293b;">{{ $category->name }}</td>
                    <td style="padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;">
                        <span style="background:#eff6ff;color:#1d4ed8;padding:.2rem .6rem;border-radius:999px;font-size:.8rem;font-weight:600;">
                            {{ $category->events_count }}
                        </span>
                    </td>
                    <td style="padding:.7rem 1rem;border-bottom:1px solid #f1f5f9;">
                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit"
                                    onclick="return confirm('{{ __('messages.admin_delete_confirm') }}')"
                                    style="background:#dc2626;color:#fff;border:none;padding:.3rem .75rem;border-radius:5px;font-size:.8rem;font-weight:600;cursor:pointer;">
                                {{ __('messages.admin_delete') }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding:2rem;text-align:center;color:#9ca3af;">
                        {{ __('messages.no_events') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>

<style>
.nav-logout-btn { background:none; border:1px solid #fff; color:#fff; padding:.3rem .7rem; border-radius:5px; cursor:pointer; }
</style>

</body>
</html>
