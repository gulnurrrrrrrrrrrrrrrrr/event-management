<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.create_event_title') }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.navbar')

<div class="event-form-card">
    <div class="card-header">
        <h2>{{ __('messages.create_event_title') }}</h2>
    </div>

    <div class="card-body">
        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label>{{ __('messages.field_title') }}</label>
                <input type="text" name="title" value="{{ old('title') }}" required>
                @error('title') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_description') }}</label>
                <textarea name="description" required>{{ old('description') }}</textarea>
                @error('description') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_date') }}</label>
                <input type="datetime-local" name="event_date" value="{{ old('event_date') }}" required>
                @error('event_date') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_location') }}</label>
                <input type="text" name="location" value="{{ old('location') }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_city') }}</label>
                <input type="text" name="city" value="{{ old('city') }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_max_participants') }}</label>
                <input type="number" name="max_participants" value="{{ old('max_participants') }}" min="1">
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_category') }}</label>
                <select name="category_id" required>
                    <option value="">{{ __('messages.field_choose_category') }}</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_images') }}</label>
                <input type="file" id="imageInput" name="images[]"
                       accept="image/jpg,image/jpeg,image/png,image/webp" multiple
                       style="width:100%;padding:.6rem;border:1px solid #cbd5e1;border-radius:6px;background:#fff;">
                <p style="font-size:.8rem;color:#9ca3af;margin-top:.4rem;">{{ __('messages.field_images_hint') }}</p>
                @error('images.*') <span class="error">{{ $message }}</span> @enderror
                <div id="previewGrid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:.75rem;margin-top:1rem;"></div>
            </div>

            <button type="submit" class="submit-btn">{{ __('messages.btn_create_event') }}</button>
        </form>

        <a href="{{ route('home') }}" class="back-link">{{ __('messages.btn_back_home') }}</a>
    </div>
</div>

<script>
document.getElementById('imageInput').addEventListener('change', function () {
    const grid = document.getElementById('previewGrid');
    const coverLabel = '{{ __('messages.img_cover') }}';
    grid.innerHTML = '';
    [...this.files].forEach((file, i) => {
        const reader = new FileReader();
        reader.onload = e => {
            grid.innerHTML += `
                <div style="position:relative;border-radius:8px;overflow:hidden;aspect-ratio:1;border:2px solid #e2e8f0;">
                    <img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">
                    ${i === 0 ? `<span style="position:absolute;top:4px;left:4px;background:#2563eb;color:#fff;font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:999px;">${coverLabel}</span>` : ''}
                </div>`;
        };
        reader.readAsDataURL(file);
    });
});
</script>

</body>
</html>
