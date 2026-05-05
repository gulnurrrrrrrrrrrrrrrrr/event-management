<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        .wrapper { max-width: 600px; margin: 40px auto; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,.1); }
        .header { background: #dc2626; color: #fff; padding: 28px 32px; }
        .header h1 { margin: 0; font-size: 22px; }
        .body { padding: 32px; color: #374151; }
        .info-box { background: #fef2f2; border-left: 4px solid #dc2626; border-radius: 4px; padding: 16px 20px; margin: 20px 0; }
        .info-box p { margin: 0; font-size: 15px; }
        .info-box strong { color: #991b1b; }
        .btn { display: inline-block; background: #2563eb; color: #fff; padding: 12px 28px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 20px; }
        .footer { background: #f8fafc; padding: 20px 32px; font-size: 12px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>EventMaster</h1>
        <p style="margin:6px 0 0; opacity:.85; font-size:14px;">Уведомление об удалении мероприятия</p>
    </div>
    <div class="body">
        <p>Здравствуйте, <strong>{{ $organizer->name }}</strong>!</p>

        <p>Сообщаем вам, что ваше мероприятие было удалено администратором платформы.</p>

        <div class="info-box">
            <p>🗑️ Удалено мероприятие: <strong>«{{ $eventTitle }}»</strong></p>
            <p style="margin-top:8px; font-size:13px; color:#6b7280;">
                Удалил: {{ $adminName }} &nbsp;|&nbsp; {{ now()->format('d.m.Y H:i') }}
            </p>
        </div>

        <p>Если вы считаете, что это произошло по ошибке — свяжитесь с администрацией платформы.</p>

        <a href="{{ url('/') }}" class="btn">Перейти на EventMaster →</a>
    </div>
    <div class="footer">
        © {{ date('Y') }} EventMaster
    </div>
</div>
</body>
</html>
