<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.edit_event_title') }}: {{ $event->title }} | EventMaster</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/events.css') }}">
    <style>
        .existing-images { display:grid; grid-template-columns:repeat(auto-fill,minmax(130px,1fr)); gap:.75rem; margin-bottom:1rem; }
        .img-thumb { position:relative; border-radius:8px; overflow:hidden; aspect-ratio:1; border:2px solid #e2e8f0; }
        .img-thumb img { width:100%; height:100%; object-fit:cover; display:block; }
        .img-thumb.is-cover { border-color:#2563eb; }
        .cover-badge { position:absolute; top:4px; left:4px; background:#2563eb; color:#fff; font-size:.65rem; font-weight:700; padding:2px 6px; border-radius:999px; }
        .img-controls { position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,.6); display:flex; justify-content:space-between; align-items:center; padding:4px 6px; }
        .img-controls label { color:#fff; font-size:.72rem; cursor:pointer; display:flex; align-items:center; gap:4px; }
        .drop-zone { border:2px dashed #93c5fd; border-radius:10px; padding:1.5rem; text-align:center; cursor:pointer; background:#eff6ff; transition:background .2s; }
        .drop-zone:hover { background:#dbeafe; border-color:#2563eb; }
        .preview-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(110px,1fr)); gap:.75rem; margin-top:.75rem; }
        .preview-item { position:relative; border-radius:8px; overflow:hidden; aspect-ratio:1; border:2px solid #e2e8f0; }
        .preview-item img { width:100%; height:100%; object-fit:cover; }
        .preview-item .remove-btn { position:absolute; top:4px; right:4px; background:rgba(220,38,38,.85); color:#fff; border:none; border-radius:50%; width:22px; height:22px; cursor:pointer; font-size:.8rem; line-height:22px; text-align:center; display:none; }
        .preview-item:hover .remove-btn { display:block; }
    </style>
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.navbar')

<div class="event-form-card">
    <div class="card-header">
        <h2>{{ __('messages.edit_event_title') }}</h2>
    </div>
    <div class="card-body">

        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;border:1px solid #fca5a5;padding:.75rem 1rem;border-radius:6px;margin-bottom:1rem;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('events.update', $event) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>{{ __('messages.field_title') }}</label>
                <input type="text" name="title" value="{{ old('title', $event->title) }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_description') }}</label>
                <textarea name="description" required>{{ old('description', $event->description) }}</textarea>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_date') }}</label>
                <input type="datetime-local" name="event_date"
                       value="{{ old('event_date', $event->event_date->format('Y-m-d\TH:i')) }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_location') }}</label>
                <input type="text" name="location" value="{{ old('location', $event->location) }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_city') }}</label>
                <input type="text" name="city" value="{{ old('city', $event->city) }}" required>
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_max_participants') }}</label>
                <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" min="1">
            </div>

            <div class="form-group">
                <label>{{ __('messages.field_category') }}</label>
                <select name="category_id" required>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            @if($event->images->count())
            <div class="form-group">
                <label>{{ __('messages.current_images') }}</label>
                <div class="existing-images">
                    @foreach($event->images as $img)
                    <div class="img-thumb {{ $img->is_cover ? 'is-cover' : '' }}">
                        <img src="{{ $img->url }}" alt="">
                        @if($img->is_cover)
                            <span class="cover-badge">{{ __('messages.img_cover') }}</span>
                        @endif
                        <div class="img-controls">
                            <label>
                                <input type="radio" name="cover_image_id" value="{{ $img->id }}"
                                       {{ $img->is_cover ? 'checked' : '' }}>
                                {{ __('messages.img_cover') }}
                            </label>
                            <label>
                                <input type="checkbox" name="delete_images[]" value="{{ $img->id }}">
                                {{ __('messages.img_delete') }}
                            </label>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="form-group">
                <label>{{ __('messages.add_images') }}</label>
                <div class="drop-zone" onclick="document.getElementById('imageInput').click()">
                    <div style="font-size:1.8rem;">➕🖼️</div>
                    <p style="color:#6b7280;font-size:.9rem;margin:.5rem 0 0;">
                        <strong style="color:#2563eb;">{{ __('messages.add_images') }}</strong>
                    </p>
                </div>
                <input type="file" id="imageInput" name="images[]"
                       accept="image/jpg,image/jpeg,image/png,image/webp"
                       multiple style="display:none">
                <p style="font-size:.8rem;color:#9ca3af;margin-top:.4rem;">{{ __('messages.field_images_hint') }}</p>
                <div class="preview-grid" id="previewGrid"></div>
            </div>

            <button type="submit" class="submit-btn">{{ __('messages.btn_save_changes') }}</button>
        </form>

        <a href="{{ route('events.show', $event) }}" class="back-link">{{ __('messages.event_back') }}</a>
    </div>
</div>

<script>
(function() {
    const input = document.getElementById('imageInput');
    const grid  = document.getElementById('previewGrid');
    let   files = [];

    input.addEventListener('change', () => { addFiles([...input.files]); input.value = ''; });

    document.querySelector('.drop-zone').addEventListener('dragover',  e => e.preventDefault());
    document.querySelector('.drop-zone').addEventListener('drop', e => {
        e.preventDefault();
        addFiles([...e.dataTransfer.files]);
    });

    function addFiles(newFiles) {
        newFiles.forEach(f => { if (f.type.startsWith('image/') && files.length < 10) files.push(f); });
        syncInput(); renderPreviews();
    }
    function removeFile(i) { files.splice(i, 1); syncInput(); renderPreviews(); }
    function syncInput() {
        const dt = new DataTransfer();
        files.forEach(f => dt.items.add(f));
        input.files = dt.files;
    }
    function renderPreviews() {
        grid.innerHTML = '';
        files.forEach((file, i) => {
            const r = new FileReader();
            r.onload = e => {
                const item = document.createElement('div');
                item.className = 'preview-item';
                item.innerHTML = `<img src="${e.target.result}"><button type="button" class="remove-btn" data-i="${i}">✕</button>`;
                item.querySelector('.remove-btn').addEventListener('click', () => removeFile(i));
                grid.appendChild(item);
            };
            r.readAsDataURL(file);
        });
    }
})();
</script>
</body>
</html>
