<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EventMaster</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#f1f5f9; color:#1e293b; }

        /* ── HEADER ── */
        header { background:#1e293b; color:#fff; padding:0; box-shadow:0 2px 8px rgba(0,0,0,.2); }
        .admin-nav { display:flex; align-items:center; justify-content:space-between; padding:0 32px; height:60px; gap:16px; flex-wrap:wrap; }
        .admin-nav-logo { font-size:1.1rem; font-weight:700; color:#fff; white-space:nowrap; }
        .admin-nav-logo span { color:#38bdf8; }
        .admin-nav-links { display:flex; align-items:center; gap:4px; flex-wrap:wrap; }
        .admin-nav-links a { color:#94a3b8; text-decoration:none; padding:6px 12px; border-radius:6px; font-size:.85rem; font-weight:500; transition:.2s; white-space:nowrap; }
        .admin-nav-links a:hover { color:#fff; background:rgba(255,255,255,.08); }
        .admin-nav-links a.active { color:#fff; background:#2563eb; }
        .nav-logout-btn { background:none; border:1px solid #475569; color:#94a3b8; padding:6px 14px; border-radius:6px; cursor:pointer; font-size:.85rem; font-weight:500; transition:.2s; }
        .nav-logout-btn:hover { border-color:#fff; color:#fff; }
        .admin-nav-right { display:flex; align-items:center; gap:12px; }

        /* ── LAYOUT ── */
        .admin-main { max-width:1280px; margin:0 auto; padding:28px 24px; }

        /* ── PAGE TITLE ── */
        .page-title { font-size:1.6rem; font-weight:700; color:#1e293b; margin-bottom:24px; }

        /* ── STAT CARDS ── */
        .stats-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:28px; }
        .stat-card { background:#fff; border-radius:12px; padding:24px 20px; box-shadow:0 1px 4px rgba(0,0,0,.06); display:flex; align-items:center; gap:16px; border-left:4px solid #2563eb; transition:.2s; }
        .stat-card:nth-child(2) { border-color:#10b981; }
        .stat-card:nth-child(3) { border-color:#f59e0b; }
        .stat-card:hover { transform:translateY(-2px); box-shadow:0 4px 12px rgba(0,0,0,.1); }
        .stat-icon { width:48px; height:48px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; background:#eff6ff; }
        .stat-card:nth-child(2) .stat-icon { background:#ecfdf5; }
        .stat-card:nth-child(3) .stat-icon { background:#fffbeb; }
        .stat-value { font-size:2rem; font-weight:800; color:#1e293b; line-height:1; }
        .stat-label { color:#64748b; font-size:.82rem; margin-top:4px; font-weight:500; }

        /* ── SECTION HEADER ── */
        .section-header { display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; }
        .section-title { font-size:1.1rem; font-weight:700; color:#1e293b; }

        /* ── TABLE ── */
        .table-card { background:#fff; border-radius:12px; box-shadow:0 1px 4px rgba(0,0,0,.06); overflow:hidden; margin-bottom:28px; }
        .table-scroll { overflow-x:auto; }
        .admin-table { width:100%; border-collapse:collapse; min-width:560px; }
        .admin-table thead tr { background:#1e293b; }
        .admin-table th { color:#cbd5e1; padding:12px 16px; text-align:left; font-size:.8rem; font-weight:600; letter-spacing:.05em; text-transform:uppercase; white-space:nowrap; }
        .admin-table td { padding:12px 16px; border-bottom:1px solid #f1f5f9; font-size:.88rem; vertical-align:middle; }
        .admin-table tbody tr:last-child td { border-bottom:none; }
        .admin-table tbody tr:hover td { background:#f8fafc; }
        .event-title-cell { font-weight:600; color:#1e293b; }
        .category-badge { background:#eff6ff; color:#1d4ed8; padding:3px 10px; border-radius:999px; font-size:.75rem; font-weight:600; white-space:nowrap; }

        /* ── ACTION BUTTONS ── */
        .btn-group { display:flex; gap:6px; flex-wrap:wrap; }
        .btn { padding:5px 12px; border:none; border-radius:6px; cursor:pointer; font-size:.78rem; font-weight:600; text-decoration:none; display:inline-block; white-space:nowrap; transition:.15s; }
        .btn:hover { opacity:.85; transform:translateY(-1px); }
        .btn-view   { background:#eff6ff; color:#2563eb; }
        .btn-edit   { background:#fffbeb; color:#d97706; }
        .btn-danger { background:#fef2f2; color:#dc2626; }

        /* ── CHARTS GRID ── */
        .charts-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:16px; }
        .chart-card { background:#fff; border-radius:12px; padding:20px; box-shadow:0 1px 4px rgba(0,0,0,.06); }
        .chart-card h3 { font-size:.9rem; font-weight:700; color:#374151; margin-bottom:16px; padding-bottom:10px; border-bottom:1px solid #f1f5f9; }
        canvas { width:100% !important; height:200px !important; }

        /* ── RESPONSIVE ── */
        @media (max-width:900px) {
            .charts-grid { grid-template-columns:1fr; }
            .stats-grid  { grid-template-columns:1fr; }
        }
        @media (max-width:768px) {
            .admin-nav { padding:12px 16px; height:auto; }
            .admin-nav-logo { font-size:1rem; }
            .admin-nav-links { gap:2px; }
            .admin-nav-links a { padding:5px 8px; font-size:.78rem; }
            .admin-main { padding:16px 12px; }
            .stats-grid { grid-template-columns:1fr; gap:10px; }
            .stat-card { padding:16px; }
            .stat-value { font-size:1.6rem; }
            .charts-grid { grid-template-columns:1fr; }
        }
    </style>
</head>
<body>

@include('components.admin-navbar')

<main class="admin-main">

    <div class="page-title">{{ __('messages.admin_dashboard') }}</div>

    {{-- STAT CARDS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div>
                <div class="stat-value">{{ $eventsCount }}</div>
                <div class="stat-label">{{ __('messages.admin_events') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">👥</div>
            <div>
                <div class="stat-value">{{ $usersCount }}</div>
                <div class="stat-label">{{ __('messages.admin_users') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div>
                <div class="stat-value">{{ $categoriesCount }}</div>
                <div class="stat-label">{{ __('messages.admin_categories') }}</div>
            </div>
        </div>
    </div>

    {{-- EVENTS TABLE --}}
    <div class="section-header">
        <div class="section-title">{{ __('messages.admin_events') }}</div>
    </div>
    <div class="table-card">
        <div class="table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.field_title') }}</th>
                        <th>{{ __('messages.event_category') }}</th>
                        <th>{{ __('messages.event_organizer') }}</th>
                        <th>{{ __('messages.field_date') }}</th>
                        <th>{{ __('messages.admin_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td><span class="event-title-cell">{{ $event->title }}</span></td>
                        <td><span class="category-badge">{{ $event->category->name ?? '—' }}</span></td>
                        <td>{{ $event->organizer->name ?? '—' }}</td>
                        <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-view">{{ __('messages.admin_view') }}</a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-edit">{{ __('messages.admin_edit') }}</a>
                                <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger" onclick="return confirm('{{ __('messages.admin_delete_confirm') }}')">{{ __('messages.admin_delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align:center;color:#9ca3af;padding:2rem;">{{ __('messages.no_events') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- CHARTS --}}
    <div class="charts-grid">
        <div class="chart-card">
            <h3>📊 {{ __('messages.events_by_month') }}</h3>
            <canvas id="barChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>🗺️ {{ __('messages.events_by_city') }}</h3>
            <canvas id="pieChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>📈 {{ __('messages.registrations_growth') }}</h3>
            <canvas id="lineChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>🏷️ {{ __('messages.admin_categories') }}</h3>
            <canvas id="polarChart"></canvas>
        </div>
    </div>

</main>

<script>
const chartDefaults = { plugins: { legend: { labels: { font: { size: 11 }, boxWidth: 12 } } } };

new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{ label: '{{ __('messages.admin_events') }}', data: [4,7,3,8,6], backgroundColor: '#3b82f6', borderRadius: 6 }]
    },
    options: { ...chartDefaults, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
});

new Chart(document.getElementById('pieChart'), {
    type: 'doughnut',
    data: {
        labels: ['Astana','Almaty','Shymkent','Aktau','{{ __('messages.other') }}'],
        datasets: [{ data: [12,22,9,10,12], backgroundColor: ['#3b82f6','#f59e0b','#ef4444','#10b981','#8b5cf6'], borderWidth: 2, borderColor: '#fff' }]
    },
    options: { ...chartDefaults, cutout: '60%' }
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{ label: '{{ __('messages.participants_total') }}', data: [5,9,7,14,10,18], borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,.1)', fill: true, tension: .4, pointBackgroundColor: '#10b981' }]
    },
    options: { ...chartDefaults, scales: { y: { beginAtZero: true, grid: { color: '#f1f5f9' } }, x: { grid: { display: false } } } }
});

new Chart(document.getElementById('polarChart'), {
    type: 'polarArea',
    data: {
        labels: ['Business','Education','IT','Marketing'],
        datasets: [{ data: [11,16,7,9], backgroundColor: ['rgba(99,102,241,.7)','rgba(236,72,153,.7)','rgba(20,184,166,.7)','rgba(249,115,22,.7)'] }]
    },
    options: chartDefaults
});
</script>

</body>
</html>
