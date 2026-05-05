<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->title }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
    <style>
        .gallery-main { width:100%; max-height:420px; object-fit:cover; border-radius:10px; display:block; margin-bottom:10px; }
        .gallery-thumbs { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
        .gallery-thumb { width:80px; height:60px; object-fit:cover; border-radius:6px; cursor:pointer; border:2px solid transparent; transition:border-color .2s; }
        .gallery-thumb.active, .gallery-thumb:hover { border-color:#2563eb; }
        .no-image-placeholder { width:100%; height:280px; background:linear-gradient(135deg,#e2e8f0,#cbd5e1); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:4rem; color:#94a3b8; margin-bottom:24px; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.navbar')

<div class="event-show-card">
    <div class="card-header">
        <h1>{{ $event->title }}</h1>
    </div>

    <div class="card-body">

        @if(session('success'))
            <p style="color:green;background:#d4edda;padding:12px;border-radius:5px;margin-bottom:20px;">{{ session('success') }}</p>
        @endif
        @if(session('error'))
            <p style="color:red;background:#f8d7da;padding:12px;border-radius:5px;margin-bottom:20px;">{{ session('error') }}</p>
        @endif

        @php
            $allImages = $event->images->map(fn($img) => asset('storage/' . $img->path))->toArray();
            if (empty($allImages) && $event->image) {
                $allImages = [asset('storage/' . $event->image)];
            }
        @endphp

        @if(count($allImages) > 0)
            <img id="mainImage" class="gallery-main" src="{{ $allImages[0] }}" alt="{{ $event->title }}">
            @if(count($allImages) > 1)
            <div class="gallery-thumbs">
                @foreach($allImages as $i => $url)
                    <img class="gallery-thumb {{ $i === 0 ? 'active' : '' }}"
                         src="{{ $url }}"
                         alt="{{ __('messages.event_photos') }} {{ $i + 1 }}"
                         onclick="switchImage(this, '{{ $url }}')">
                @endforeach
            </div>
            @endif
        @else
            <div class="no-image-placeholder">📅</div>
        @endif

        <div class="event-info">
            <p><strong>{{ __('messages.field_date') }}:</strong> {{ $event->event_date->format('d.m.Y H:i') }}</p>
            <p><strong>{{ __('messages.event_location') }}:</strong> {{ $event->location }}, {{ $event->city }}</p>
            @if($event->max_participants)
                <p><strong>{{ __('messages.event_seats') }}:</strong> {{ $event->registeredUsers->count() }} / {{ $event->max_participants }}</p>
            @endif
            @if($event->category)
                <p><strong>{{ __('messages.event_category') }}:</strong> {{ $event->category->name }}</p>
            @endif
            @if($event->organizer)
                <p><strong>{{ __('messages.event_organizer') }}:</strong> {{ $event->organizer->name }}</p>
            @endif
        </div>

        <div class="event-description">
            <h3>{{ __('messages.event_description') }}</h3>
            <p>{{ $event->description }}</p>
        </div>

        @auth
            @php $user = auth()->user(); @endphp

            @if($user->isUser())
                @if($event->registeredUsers->contains($user->id))
                    <form action="{{ route('events.unregister', $event) }}" method="POST" style="margin-top:20px;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:#6b7280;color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-size:15px;">
                            {{ __('messages.event_unregister_btn') }}
                        </button>
                    </form>
                @elseif(!$event->max_participants || $event->registeredUsers->count() < $event->max_participants)
                    <form action="{{ route('events.register', $event) }}" method="POST" style="margin-top:20px;">
                        @csrf
                        <button type="submit" style="background:#10b981;color:#fff;border:none;padding:12px 24px;border-radius:8px;cursor:pointer;font-size:15px;">
                            {{ __('messages.event_register_btn') }}
                        </button>
                    </form>
                @else
                    <p style="color:#ef4444;margin-top:20px;font-weight:600;">{{ __('messages.event_seats_full') }}</p>
                @endif
            @endif

            @if($user->canManageEvent($event))
                <div class="action-buttons" style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap;">
                    <a href="{{ route('events.edit', $event) }}" class="btn-edit">{{ __('messages.event_edit') }}</a>
                    <form action="{{ route('events.destroy', $event) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('{{ __('messages.event_delete_confirm') }}')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn-delete">{{ __('messages.event_delete') }}</button>
                    </form>
                </div>
            @endif

            @if($user->isAdmin() || $user->isSuperAdmin())
                <div style="margin-top:12px;">
                    <a href="{{ route('admin.events.participants', $event) }}" style="color:#3b82f6;">
                        👥 {{ __('messages.event_participants') }} ({{ $event->registeredUsers->count() }})
                    </a>
                </div>
            @endif
        @endauth

        <div style="margin-top:40px;">
            <a href="{{ route('home') }}" class="btn-back">{{ __('messages.event_back') }}</a>
        </div>
    </div>
</div>

<script>
function switchImage(thumb, url) {
    document.getElementById('mainImage').src = url;
    document.querySelectorAll('.gallery-thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>

</body>
</html>
