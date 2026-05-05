
<div class="language-switcher" style="display: inline-block;">
    <div style="display: flex; gap: 4px; background: #f1f5f9; padding: 4px; border-radius: 8px;">
        @foreach(config('app.supported_locales', ['ru', 'en', 'kk']) as $locale)
            <a href="{{ route('lang.switch', $locale) }}"
               class="lang-btn {{ app()->getLocale() === $locale ? 'active' : '' }}"
               style="padding: 6px 12px; 
                      text-decoration: none; 
                      border-radius: 6px; 
                      font-weight: 500;
                      {{ app()->getLocale() === $locale ? 'background: #2563eb; color: white;' : 'color: #64748b;' }}">
                {{ strtoupper($locale) }}
            </a>
        @endforeach
    </div>
</div>