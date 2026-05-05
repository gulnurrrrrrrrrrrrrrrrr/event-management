<header class="site-header">
    <nav class="site-nav">

        {{-- Logo --}}
        <a href="{{ route('home') }}" class="site-nav__logo">⚡ EventMaster</a>

        {{-- Desktop links --}}
        <ul class="site-nav__links" id="navMenu">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav_home') }}</a></li>
            <li><a href="{{ route('events.index') }}" class="{{ request()->routeIs('events.*') ? 'active' : '' }}">{{ __('messages.nav_events') }}</a></li>

            @auth
                @if(auth()->user()->isOrganizer() || auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <li><a href="{{ route('events.create') }}" class="{{ request()->routeIs('events.create') ? 'active' : '' }}">{{ __('messages.nav_create_event') }}</a></li>
                @endif
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                    <li><a href="{{ route('admin.index') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">{{ __('messages.nav_admin') }}</a></li>
                @endif
            @endauth

            {{-- Language switcher --}}
            <li class="lang-item">
                <div class="site-nav__lang">
                    <a href="{{ route('lang.switch', 'ru') }}" class="lang-btn {{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'kk') }}" class="lang-btn {{ app()->getLocale() === 'kk' ? 'active' : '' }}">KZ</a>
                </div>
            </li>

            @auth
                <li class="user-item">
                    <span class="site-nav__user">👤 {{ auth()->user()->name }}</span>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="site-nav__logout">{{ __('messages.nav_logout') }}</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="site-nav__login">{{ __('messages.nav_login') }}</a></li>
                <li><a href="{{ route('register') }}" class="site-nav__register">{{ __('messages.nav_register') }}</a></li>
            @endauth
        </ul>

        {{-- Burger button --}}
        <button class="site-nav__burger" id="burgerBtn" aria-label="Menu" aria-expanded="false">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>
</header>

{{-- Overlay --}}
<div class="nav-overlay" id="navOverlay"></div>

<style>
/* ═══════════════════════════════════════════
   NAVBAR STYLES
═══════════════════════════════════════════ */
.site-header {
    background: #1e293b;
    position: sticky;
    top: 0;
    z-index: 1000;
    box-shadow: 0 2px 10px rgba(0,0,0,.25);
}

.site-nav {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

/* Logo */
.site-nav__logo {
    font-size: 1.15rem;
    font-weight: 800;
    color: #38bdf8;
    text-decoration: none;
    white-space: nowrap;
    flex-shrink: 0;
}
.site-nav__logo:hover { color: #7dd3fc; }

/* Nav links list */
.site-nav__links {
    display: flex;
    align-items: center;
    gap: 4px;
    list-style: none;
    margin: 0;
    padding: 0;
}

.site-nav__links li a {
    color: #94a3b8;
    text-decoration: none;
    padding: 6px 12px;
    border-radius: 7px;
    font-size: .88rem;
    font-weight: 500;
    transition: background .18s, color .18s;
    white-space: nowrap;
    display: block;
}
.site-nav__links li a:hover { color: #fff; background: rgba(255,255,255,.09); }
.site-nav__links li a.active { color: #fff; background: #2563eb; }

/* Language */
.site-nav__lang { display: flex; gap: 4px; }
.lang-btn {
    padding: 4px 10px;
    border-radius: 5px;
    font-size: .78rem;
    font-weight: 700;
    text-decoration: none;
    border: 1px solid #334155;
    color: #94a3b8;
    transition: .18s;
}
.lang-btn:hover { border-color: #64748b; color: #fff; }
.lang-btn.active { background: #2563eb; border-color: #2563eb; color: #fff; }

/* User name */
.site-nav__user {
    color: #cbd5e1;
    font-size: .85rem;
    font-weight: 500;
    white-space: nowrap;
}

/* Logout */
.site-nav__logout {
    background: none;
    border: 1px solid #475569;
    color: #94a3b8;
    padding: 5px 14px;
    border-radius: 7px;
    cursor: pointer;
    font-size: .85rem;
    font-weight: 500;
    transition: .18s;
    white-space: nowrap;
}
.site-nav__logout:hover { border-color: #ef4444; color: #ef4444; }

/* Login / Register */
.site-nav__login {
    border: 1px solid #475569 !important;
}
.site-nav__register {
    background: #2563eb !important;
    color: #fff !important;
    border-radius: 7px;
    padding: 6px 14px;
}
.site-nav__register:hover { background: #1d4ed8 !important; }

/* ── BURGER BUTTON ── */
.site-nav__burger {
    display: none;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 5px;
    width: 40px;
    height: 40px;
    background: none;
    border: 1px solid #334155;
    border-radius: 8px;
    cursor: pointer;
    padding: 6px;
    flex-shrink: 0;
    transition: border-color .2s;
}
.site-nav__burger:hover { border-color: #64748b; }
.site-nav__burger span {
    display: block;
    width: 20px;
    height: 2px;
    background: #94a3b8;
    border-radius: 2px;
    transition: transform .3s, opacity .3s, width .3s;
    transform-origin: center;
}

/* Burger → X animation */
.site-nav__burger.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
.site-nav__burger.open span:nth-child(2) { opacity: 0; width: 0; }
.site-nav__burger.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

/* ── OVERLAY ── */
.nav-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.45);
    z-index: 998;
    backdrop-filter: blur(2px);
}
.nav-overlay.show { display: block; }

/* ══════════════════════════════════════════
   MOBILE MENU  (≤ 768px)
══════════════════════════════════════════ */
@media (max-width: 768px) {

    .site-nav__burger { display: flex; }

    .site-nav__links {
        position: fixed;
        top: 0;
        right: -100%;
        width: 72vw;
        max-width: 300px;
        height: 100dvh;
        background: #0f172a;
        flex-direction: column;
        align-items: flex-start;
        gap: 2px;
        padding: 72px 20px 32px;
        z-index: 999;
        transition: right .3s cubic-bezier(.4,0,.2,1);
        overflow-y: auto;
        box-shadow: -4px 0 24px rgba(0,0,0,.4);
    }

    .site-nav__links.open { right: 0; }

    /* Full-width links on mobile */
    .site-nav__links li { width: 100%; }
    .site-nav__links li a {
        font-size: .95rem;
        padding: 10px 14px;
        width: 100%;
        border-radius: 8px;
    }

    /* Divider before lang */
    .lang-item {
        border-top: 1px solid #1e293b;
        padding-top: 12px;
        margin-top: 8px;
        width: 100%;
    }
    .site-nav__lang { padding: 4px 14px; }
    .lang-btn { padding: 5px 12px; font-size: .82rem; }

    /* User item */
    .user-item {
        border-top: 1px solid #1e293b;
        padding-top: 12px;
        margin-top: 8px;
        width: 100%;
    }
    .site-nav__user { padding: 6px 14px; display: block; font-size: .88rem; }

    /* Logout full width */
    .site-nav__logout {
        width: calc(100% - 28px);
        margin: 4px 14px;
        padding: 10px 14px;
        text-align: center;
    }

    /* Register btn full width */
    .site-nav__register {
        display: block !important;
        width: calc(100% - 28px) !important;
        margin: 4px 14px !important;
        text-align: center;
        padding: 10px 14px !important;
    }
}
</style>

<script>
(function () {
    const burger  = document.getElementById('burgerBtn');
    const menu    = document.getElementById('navMenu');
    const overlay = document.getElementById('navOverlay');

    function openMenu() {
        menu.classList.add('open');
        burger.classList.add('open');
        overlay.classList.add('show');
        burger.setAttribute('aria-expanded', 'true');
        document.body.style.overflow = 'hidden';
    }

    function closeMenu() {
        menu.classList.remove('open');
        burger.classList.remove('open');
        overlay.classList.remove('show');
        burger.setAttribute('aria-expanded', 'false');
        document.body.style.overflow = '';
    }

    burger.addEventListener('click', () => {
        menu.classList.contains('open') ? closeMenu() : openMenu();
    });

    overlay.addEventListener('click', closeMenu);

    // Close on ESC
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeMenu(); });

    // Close when link clicked (navigating)
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', closeMenu));
})();
</script>
