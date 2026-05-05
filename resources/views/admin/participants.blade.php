<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.participants_title') }}: {{ $event->title }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <style>
        .admin-main { padding: 2rem; }
        .admin-container { max-width: 1000px; margin: 0 auto; }
        .back-link { display:inline-flex; align-items:center; gap:.4rem; color:#2563eb; text-decoration:none; font-size:.9rem; margin-bottom:1.5rem; }
        .back-link:hover { text-decoration:underline; }
        .page-title { font-size:1.6rem; font-weight:700; color:#1e293b; margin-bottom:.25rem; }
        .page-subtitle { color:#6b7280; font-size:.9rem; margin-bottom:1.5rem; }
        .stat-badges { display:flex; gap:.75rem; margin-bottom:1.5rem; flex-wrap:wrap; }
        .stat-badge { background:#eff6ff; color:#1d4ed8; border:1px solid #bfdbfe; padding:.4rem 1rem; border-radius:999px; font-size:.85rem; font-weight:600; }
        .stat-badge.green { background:#f0fdf4; color:#15803d; border-color:#bbf7d0; }
        .table-wrapper { background:#fff; border-radius:10px; box-shadow:0 1px 4px rgba(0,0,0,.08); overflow:hidden; }
        .participants-table { width:100%; border-collapse:collapse; }
        .participants-table th { background:#1e293b; color:#fff; padding:.75rem 1rem; text-align:left; font-size:.85rem; }
        .participants-table td { padding:.75rem 1rem; border-bottom:1px solid #f1f5f9; font-size:.9rem; vertical-align:middle; }
        .participants-table tr:last-child td { border-bottom:none; }
        .participants-table tr:hover td { background:#f8fafc; }
        .avatar { width:36px; height:36px; border-radius:50%; object-fit:cover; background:#e2e8f0; display:inline-flex; align-items:center; justify-content:center; font-weight:700; color:#64748b; font-size:.9rem; }
        .empty-state { text-align:center; padding:3rem; color:#9ca3af; }
        .empty-state .icon { font-size:3rem; margin-bottom:1rem; }
        .nav-logout-btn { background:none; border:1px solid #fff; color:#fff; padding:.3rem .7rem; border-radius:5px; cursor:pointer; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.admin-navbar')

<main class="admin-main">
    <div class="admin-container">

        <a href="{{ route('admin.index') }}" class="back-link">{{ __('messages.back_to_dashboard') }}</a>

        <h1 class="page-title">{{ __('messages.participants_title') }}</h1>
        <p class="page-subtitle">{{ $event->title }}</p>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:1rem 1.25rem; margin-bottom:1.5rem; font-size:.9rem; color:#374151;">
            📅 <strong>{{ $event->event_date->format('d.m.Y H:i') }}</strong>
            &nbsp;·&nbsp;
            📍 {{ $event->city }}, {{ $event->location }}
            @if($event->organizer)
                &nbsp;·&nbsp;
                👤 {{ __('messages.event_organizer') }}: {{ $event->organizer->name }}
            @endif
        </div>

        <div class="stat-badges">
            <span class="stat-badge">👥 {{ __('messages.participants_total') }}: {{ $participants->count() }}</span>
            @if($event->max_participants)
                <span class="stat-badge {{ $participants->count() >= $event->max_participants ? '' : 'green' }}">
                    {{ __('messages.event_seats') }}: {{ $participants->count() }} / {{ $event->max_participants }}
                </span>
                @if($participants->count() < $event->max_participants)
                    <span class="stat-badge green">✅ {{ __('messages.participants_free') }}: {{ $event->max_participants - $participants->count() }}</span>
                @else
                    <span class="stat-badge" style="background:#fef2f2;color:#dc2626;border-color:#fecaca;">🔴 {{ __('messages.participants_no_seats') }}</span>
                @endif
            @endif
        </div>

        <div class="table-wrapper">
            @if($participants->count() > 0)
            <table class="participants-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.participants_column_num') }}</th>
                        <th>{{ __('messages.participants_column_name') }}</th>
                        <th>{{ __('messages.participants_column_email') }}</th>
                        <th>{{ __('messages.participants_column_city') }}</th>
                        <th>{{ __('messages.participants_column_date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($participants as $i => $participant)
                    <tr>
                        <td style="color:#9ca3af; font-size:.85rem;">{{ $i + 1 }}</td>
                        <td>
                            <div style="display:flex; align-items:center; gap:.75rem;">
                                @if($participant->avatar)
                                    <img src="{{ asset('storage/' . $participant->avatar) }}"
                                         class="avatar" alt="{{ $participant->name }}">
                                @else
                                    <div class="avatar">{{ mb_strtoupper(mb_substr($participant->name, 0, 1)) }}</div>
                                @endif
                                <div>
                                    <div style="font-weight:600; color:#1e293b;">{{ $participant->name }}</div>
                                    @if($participant->age)
                                        <div style="font-size:.78rem; color:#9ca3af;">{{ $participant->age }}</div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="color:#6b7280;">{{ $participant->email }}</td>
                        <td>{{ $participant->city ?? '—' }}</td>
                        <td style="color:#6b7280; font-size:.85rem;">
                            {{ $participant->pivot->created_at
                                ? \Carbon\Carbon::parse($participant->pivot->created_at)->format('d.m.Y H:i')
                                : '—' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="empty-state">
                <div class="icon">👤</div>
                <p style="font-size:1.1rem; font-weight:600; color:#374151; margin-bottom:.5rem;">{{ __('messages.participants_empty') }}</p>
                <p>{{ __('messages.participants_nobody') }}</p>
            </div>
            @endif
        </div>

    </div>
</main>

</body>
</html>
