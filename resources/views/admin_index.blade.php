<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | EventMaster</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<header>
    <nav>
        <h2>EventMaster — Admin Panel</h2>
        <ul>
            <li><a href="{{ route('home') }}">Главная</a></li>
            <li><a href="{{ route('admin.index') }}" class="active">Dashboard</a></li>
            <li><a href="{{ route('admin.users') }}">Пользователи</a></li>
            <li><a href="{{ route('admin.categories') }}">Категории</a></li>
            <li>
                <form action="{{ route('logout') }}" method="POST" style="display:inline">
                    @csrf
                    <button type="submit" class="nav-logout-btn">Выйти</button>
                </form>
            </li>
        </ul>
    </nav>
</header>

<main style="padding:2rem; max-width:1200px; margin:0 auto;">

    <h1 style="font-size:1.8rem; margin-bottom:1.5rem; color:#1e293b;">Dashboard</h1>

    {{-- Статистика --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(180px,1fr)); gap:1rem; margin-bottom:2rem;">
        <div class="stat-card">
            <div class="stat-value">{{ $eventsCount }}</div>
            <div class="stat-label">Мероприятий</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $usersCount }}</div>
            <div class="stat-label">Пользователей</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $categoriesCount }}</div>
            <div class="stat-label">Категорий</div>
        </div>
    </div>

    {{-- Таблица мероприятий --}}
    <h2 style="font-size:1.3rem; margin-bottom:1rem; color:#1e293b;">Все мероприятия</h2>
    <div style="overflow-x:auto; background:#fff; border-radius:8px; box-shadow:0 1px 4px rgba(0,0,0,.08); margin-bottom:2rem;">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Категория</th>
                    <th>Организатор</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr>
                    <td>{{ $event->title }}</td>
                    <td>{{ $event->category->name ?? '—' }}</td>
                    <td>{{ $event->organizer->name ?? '—' }}</td>
                    <td>{{ \Carbon\Carbon::parse($event->event_date)->format('d.m.Y H:i') }}</td>
                    <td>
                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary">Просмотр</a>
                        <a href="{{ route('events.edit', $event) }}" class="btn btn-sm" style="background:#f59e0b;color:#fff;">Редакт.</a>
                        <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('Удалить?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;color:#6b7280;">Нет мероприятий</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Графики --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1.5rem;">
        <div class="chart-card"><h3>Мероприятия по месяцам</h3><canvas id="barChart"></canvas></div>
        <div class="chart-card"><h3>Доля по городам</h3><canvas id="pieChart"></canvas></div>
        <div class="chart-card"><h3>Рост регистраций</h3><canvas id="lineChart"></canvas></div>
        <div class="chart-card"><h3>Категории</h3><canvas id="polarChart"></canvas></div>
    </div>

</main>

<style>
.stat-card { background:#fff; border-radius:8px; padding:1.5rem; box-shadow:0 1px 4px rgba(0,0,0,.08); text-align:center; }
.stat-value { font-size:2.5rem; font-weight:700; color:#2563eb; }
.stat-label { color:#6b7280; font-size:.9rem; margin-top:.25rem; }
.chart-card { background:#fff; border-radius:8px; padding:1.25rem; box-shadow:0 1px 4px rgba(0,0,0,.08); }
.chart-card h3 { font-size:1rem; margin-bottom:1rem; color:#374151; }
.admin-table { width:100%; border-collapse:collapse; }
.admin-table th { background:#1e293b; color:#fff; padding:.75rem 1rem; text-align:left; font-size:.85rem; }
.admin-table td { padding:.7rem 1rem; border-bottom:1px solid #f1f5f9; font-size:.9rem; }
.btn { padding:.35rem .8rem; border:none; border-radius:5px; cursor:pointer; font-size:.8rem; font-weight:600; text-decoration:none; display:inline-block; }
.btn-primary { background:#2563eb; color:#fff; }
.btn-danger  { background:#dc2626; color:#fff; }
.btn-sm { padding:.3rem .65rem; }
.nav-logout-btn { background:none; border:1px solid #fff; color:#fff; padding:.3rem .7rem; border-radius:5px; cursor:pointer; }
</style>

<script>
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May'],
        datasets: [{ label: 'Мероприятий', data: [4,7,3,8,6], backgroundColor: '#3b82f6' }]
    },
    options: { plugins: { legend: { display: false } } }
});

new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Astana','Almaty','Shymkent','Aktau','Другие'],
        datasets: [{ data: [12,22,9,10,12], backgroundColor: ['#3b82f6','#f59e0b','#ef4444','#10b981','#8b5cf6'] }]
    }
});

new Chart(document.getElementById('lineChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{ label: 'Регистрации', data: [5,9,7,14,10,18], borderColor: '#3b82f6', backgroundColor: 'rgba(59,130,246,.2)', fill: true, tension: .4 }]
    }
});

new Chart(document.getElementById('polarChart'), {
    type: 'polarArea',
    data: {
        labels: ['Business','Education','IT','Marketing'],
        datasets: [{ data: [11,16,7,9], backgroundColor: ['#6366f1','#ec4899','#14b8a6','#f97316'] }]
    }
});
</script>
</body>
</html>
