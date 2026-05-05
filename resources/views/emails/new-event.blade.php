<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #2563eb; color: #fff; padding: 28px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #374151; }
        .event-card { background: #f1f5f9; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .event-card h2 { margin: 0 0 8px; color: #1e293b; font-size: 18px; }
        .meta { font-size: 14px; color: #6b7280; line-height: 2; }
        .btn { display: inline-block; background: #2563eb; color: #fff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #f8fafc; padding: 20px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>EventMaster</h1>
        <p style="margin:6px 0 0; opacity:.85; font-size:14px;">На сайте появилось новое мероприятие</p>
    </div>
    <div class="body">
        <p>Здравствуйте!</p>
        <p>На платформе <strong>EventMaster</strong> опубликовано новое мероприятие, которое может вас заинтересовать:</p>

        <div class="event-card">
            <h2>{{ $event->title }}</h2>
            <div class="meta">
                📅 <strong>Дата:</strong> {{ $event->event_date->format('d.m.Y H:i') }}<br>
                📍 <strong>Место:</strong> {{ $event->city }}, {{ $event->location }}<br>
                🗂️ <strong>Категория:</strong> {{ $event->category->name ?? '—' }}<br>
                👤 <strong>Организатор:</strong> {{ $event->organizer->name ?? '—' }}<br>
                @if($event->max_participants)
                👥 <strong>Мест:</strong> {{ $event->max_participants }}
                @endif
            </div>
        </div>

        <p>{{ Str::limit($event->description, 200) }}</p>

        <a href="{{ route('events.show', $event) }}" class="btn">Узнать подробнее →</a>
    </div>
    <div class="footer">
        Вы получили это письмо, потому что зарегистрированы на EventMaster.<br>
        © {{ date('Y') }} EventMaster
    </div>
</div>
</body>
</html>
