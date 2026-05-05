<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - EventMaster</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>
<body>

<header>
    <nav>
        <h2>EventMaster - Admin Dashboard</h2>
        <ul>
            <li><a href="/">Главная</a></li>
            <li><a href="#">Мероприятия</a></li>
            <li><a href="#">Пользователи</a></li>
            <li><a href="#">Выйти</a></li>
            <!-- Переключатель языка -->
    @include('components.language-switcher')
        </ul>
    </nav>
</header>

<main>
    <section>
        <h3>Доля по городам</h3>
        <canvas id="pieChart"></canvas>
    </section>

    <section>
        <h3>Мероприятия по месяцам</h3>
        <canvas id="barChart"></canvas>
    </section>
    <section>
    <h3>Тип поколения</h3>
    <canvas id="doughnutChart"></canvas>
</section>

<section>
    <h3>Категории мероприятий </h3>
    <canvas id="polarChart"></canvas>
</section>

<section>
    <h3>Рост регистраций</h3>
    <canvas id="lineChart"></canvas>
</section>
</main>

<script>
    
const doughnutCtx = document.getElementById('doughnutChart');

new Chart(doughnutCtx, {
    type: 'doughnut',
    data: {
        labels: ['18-25', '26-40', '41-65'],
        datasets: [{
            label: 'Тип поколения',
            data: [23, 30, 11],
            backgroundColor: [
                '#3b82f6',
                '#10b981',
                '#f59e0b'
            ]
        }]
    }
});


const polarCtx = document.getElementById('polarChart');

new Chart(polarCtx, {
    type: 'polarArea',
    data: {
        labels: ['Business', 'Education', 'IT', 'Marketing'],
        datasets: [{
            label: 'Категории',
            data: [11, 16, 7, 9],
            backgroundColor: [
                '#6366f1',
                '#ec4899',
                '#14b8a6',
                '#f97316'
            ]
        }]
    }
});



const lineCtx = document.getElementById('lineChart');

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Новые регистрации',
            data: [5, 9, 7, 14, 10, 18],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.2)',
            fill: true,
            tension: 0.4
        }]
    }
});
    
    const pieCtx = document.getElementById('pieChart');

    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['Astana', 'Almaty', 'Shumkent', 'Aktau', 'Others'],
            datasets: [{
                label: 'City',
                data: [12, 22, 9, 10, 12],
                backgroundColor: [
                    'green',
                    'orange',
                    'red',
                    'blue',
                    '#ec4899'
                ]
            }]
        }
    });

    const barCtx = document.getElementById('barChart');

    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
            datasets: [{
                label: 'Количество мероприятий',
                data: [4, 7, 3, 8, 6],
                backgroundColor: [
                    'blue',
                    'blue',
                    'blue',
                    'blue',
                    'blue'
                ]
            }]
        }
    });
</script>

</body>
</html>