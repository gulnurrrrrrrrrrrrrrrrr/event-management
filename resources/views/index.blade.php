<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <style>
        .lang-switcher { display:flex; gap:4px; align-items:center; }
        .lang-btn {
            padding: 4px 10px;
            border-radius: 5px;
            font-size: .8rem;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(255,255,255,0.4);
            color: #fff;
            transition: background .2s;
        }
        .lang-btn:hover { background: rgba(255,255,255,0.2); }
        .lang-btn.active { background: rgba(255,255,255,0.3); border-color: #fff; }
        .event-card-img { width:100%; height:200px; object-fit:cover; display:block; }
        .event-card-img-placeholder {
            width:100%; height:200px;
            background: linear-gradient(135deg,#e2e8f0,#cbd5e1);
            display:flex; align-items:center; justify-content:center;
            font-size:2.5rem; color:#94a3b8;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>
@include('components.navbar')

<main>
    <section class="events-section">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h2>{{ __('messages.upcoming_events') }}</h2>
        </div>

        @if(session('success'))
            <p style="color:green; background:#d4edda; padding:12px; border-radius:5px; margin-bottom:20px;">
                {{ session('success') }}
            </p>
        @endif
        @if(session('error'))
            <p style="color:red; background:#f8d7da; padding:12px; border-radius:5px; margin-bottom:20px;">
                {{ session('error') }}
            </p>
        @endif

        <div class="events-container">
            @forelse($events as $event)
                <div class="event-card">
                    @php
                        $coverImage = $event->images->where('is_cover', true)->first()
                                   ?? $event->images->first();
                    @endphp

                    @if($coverImage)
                        <img class="event-card-img"
                             src="{{ asset('storage/' . $coverImage->path) }}"
                             alt="{{ $event->title }}">
                    @elseif($event->image)
                        <img class="event-card-img"
                             src="{{ asset('storage/' . $event->image) }}"
                             alt="{{ $event->title }}">
                    @else
                        <div class="event-card-img-placeholder">📅</div>
                    @endif

                    @if(isset($event->images) && $event->images->count() > 1)
                        <div style="font-size:.75rem;color:#6b7280;padding:4px 10px;background:#f8fafc;">
                            🖼 {{ $event->images->count() }} {{ __('messages.event_photos') }}
                        </div>
                    @endif

                    <div style="padding:14px;">
                        <h3>{{ $event->title }}</h3>
                        <p><strong>{{ __('messages.event_date') }}:</strong> {{ $event->event_date->format('d.m.Y H:i') }}</p>
                        <p><strong>{{ __('messages.event_location') }}:</strong> {{ $event->location }}, {{ $event->city }}</p>

                        @if($event->category)
                            <p><strong>{{ __('messages.event_category') }}:</strong> {{ $event->category->name }}</p>
                        @endif

                        @if($event->organizer)
                            <p><strong>{{ __('messages.event_organizer') }}:</strong> {{ $event->organizer->name }}</p>
                        @endif

                        <a href="{{ route('events.show', $event) }}" class="details-btn">
                            {{ __('messages.event_details') }}
                        </a>
                    </div>
                </div>
            @empty
                <p>{{ __('messages.no_events') }}</p>
            @endforelse
        </div>

        <div style="margin-top:2rem; text-align:center;">
            {{ $events->links() }}
        </div>
    </section>
</main>

<footer>
    <p>© 2026 EventMaster. {{ __('messages.footer_rights') }}</p>
</footer>
</body>
</html>
