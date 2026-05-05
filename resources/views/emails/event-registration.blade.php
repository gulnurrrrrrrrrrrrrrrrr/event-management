<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #10b981; color: #fff; padding: 28px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #374151; }
        .event-card { background: #f0fdf4; border-left: 4px solid #10b981; border-radius: 4px; padding: 16px 20px; margin: 20px 0; }
        .event-card h2 { margin: 0 0 10px; color: #065f46; font-size: 18px; }
        .meta { font-size: 14px; color: #374151; line-height: 2.1; }
        .btn { display: inline-block; background: #10b981; color: #fff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #f8fafc; padding: 20px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>EventMaster</h1>
        <p style="margin:6px 0 0; opacity:.85; font-size:14px;">Вы успешно записались!</p>
    </div>
    <div class="body">
        <p>Здравствуйте, <strong>{{ $user->name }}</strong>!</p>
        <p>Вы успешно зарегистрированы на мероприятие:</p>

        <div class="event-card">
            <h2>{{ $event->title }}</h2>
            <div class="meta">
                📅 <strong>Дата:</strong> {{ $event->event_date->format('d.m.Y H:i') }}<br>
                📍 <strong>Место:</strong> {{ $event->city }}, {{ $event->location }}<br>
                👤 <strong>Организатор:</strong> {{ $event->organizer->name ?? '—' }}<br>
                @if($event->max_participants)
                👥 <strong>Всего мест:</strong> {{ $event->max_participants }}
                @endif
            </div>
        </div>

        <p>Сохраните это письмо — в нём содержится информация о мероприятии. Вы можете отменить запись в любой момент через наш сайт.</p>

        <a href="{{ route('events.show', $event) }}" class="btn">Перейти к мероприятию →</a>
    </div>
    <div class="footer">
        © {{ date('Y') }} EventMaster
    </div>
</div>
</body>
</html>
