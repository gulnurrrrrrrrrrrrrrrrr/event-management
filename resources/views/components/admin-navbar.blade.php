<header class="admin-header">
    <nav class="admin-nav">
        <a href="{{ route('admin.index') }}" class="admin-nav__logo">
            ⚡ EventMaster <span>Admin</span>
        </a>

        <ul class="admin-nav__links" id="adminNavMenu">
            <li><a href="{{ route('home') }}"         class="{{ request()->routeIs('home') ? 'active' : '' }}">{{ __('messages.nav_home') }}</a></li>
            <li><a href="{{ route('admin.index') }}"  class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">{{ __('messages.admin_dashboard') }}</a></li>
            <li><a href="{{ route('admin.users') }}"  class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">{{ __('messages.admin_users') }}</a></li>
            <li><a href="{{ route('admin.categories') }}" class="{{ request()->routeIs('admin.categories') ? 'active' : '' }}">{{ __('messages.admin_categories') }}</a></li>

            <li class="lang-item">
                <div class="admin-nav__lang">
                    <a href="{{ route('lang.switch', 'ru') }}" class="lang-btn {{ app()->getLocale() === 'ru' ? 'active' : '' }}">RU</a>
                    <a href="{{ route('lang.switch', 'en') }}" class="lang-btn {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                    <a href="{{ route('lang.switch', 'kk') }}" class="lang-btn {{ app()->getLocale() === 'kk' ? 'active' : '' }}">KZ</a>
                </div>
            </li>

            <li class="logout-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="admin-nav__logout">{{ __('messages.nav_logout') }}</button>
                </form>
            </li>
        </ul>

        <button class="admin-nav__burger" id="adminBurger" aria-label="Menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>
    </nav>
</header>

<div class="nav-overlay" id="adminOverlay"></div>

<style>
.admin-header { background:#1e293b; position:sticky; top:0; z-index:1000; box-shadow:0 2px 10px rgba(0,0,0,.25); }
.admin-nav { max-width:1280px; margin:0 auto; padding:0 24px; height:60px; display:flex; align-items:center; justify-content:space-between; gap:16px; }
.admin-nav__logo { font-size:1.05rem; font-weight:800; color:#fff; text-decoration:none; white-space:nowrap; flex-shrink:0; }
.admin-nav__logo span { color:#38bdf8; }
.admin-nav__logo:hover { opacity:.85; }

.admin-nav__links { display:flex; align-items:center; gap:4px; list-style:none; margin:0; padding:0; }
.admin-nav__links li a { color:#94a3b8; text-decoration:none; padding:6px 12px; border-radius:7px; font-size:.87rem; font-weight:500; transition:.18s; white-space:nowrap; display:block; }
.admin-nav__links li a:hover { color:#fff; background:rgba(255,255,255,.09); }
.admin-nav__links li a.active { color:#fff; background:#2563eb; }

.admin-nav__lang { display:flex; gap:4px; }
.lang-btn { padding:4px 10px; border-radius:5px; font-size:.78rem; font-weight:700; text-decoration:none; border:1px solid #334155; color:#94a3b8; transition:.18s; }
.lang-btn:hover { border-color:#64748b; color:#fff; }
.lang-btn.active { background:#2563eb; border-color:#2563eb; color:#fff; }

.admin-nav__logout { background:none; border:1px solid #475569; color:#94a3b8; padding:5px 14px; border-radius:7px; cursor:pointer; font-size:.85rem; font-weight:500; transition:.18s; white-space:nowrap; }
.admin-nav__logout:hover { border-color:#ef4444; color:#ef4444; }

.admin-nav__burger { display:none; flex-direction:column; justify-content:center; align-items:center; gap:5px; width:40px; height:40px; background:none; border:1px solid #334155; border-radius:8px; cursor:pointer; padding:6px; flex-shrink:0; transition:border-color .2s; }
.admin-nav__burger:hover { border-color:#64748b; }
.admin-nav__burger span { display:block; width:20px; height:2px; background:#94a3b8; border-radius:2px; transition:transform .3s, opacity .3s, width .3s; transform-origin:center; }
.admin-nav__burger.open span:nth-child(1) { transform:translateY(7px) rotate(45deg); }
.admin-nav__burger.open span:nth-child(2) { opacity:0; width:0; }
.admin-nav__burger.open span:nth-child(3) { transform:translateY(-7px) rotate(-45deg); }

@media (max-width: 768px) {
    .admin-nav__burger { display:flex; }
    .admin-nav__links { position:fixed; top:0; right:-100%; width:72vw; max-width:300px; height:100dvh; background:#0f172a; flex-direction:column; align-items:flex-start; gap:2px; padding:72px 20px 32px; z-index:999; transition:right .3s cubic-bezier(.4,0,.2,1); overflow-y:auto; box-shadow:-4px 0 24px rgba(0,0,0,.4); }
    .admin-nav__links.open { right:0; }
    .admin-nav__links li { width:100%; }
    .admin-nav__links li a { font-size:.95rem; padding:10px 14px; width:100%; border-radius:8px; }
    .lang-item { border-top:1px solid #1e293b; padding-top:12px; margin-top:8px; width:100%; }
    .admin-nav__lang { padding:4px 14px; }
    .lang-btn { padding:5px 12px; font-size:.82rem; }
    .logout-item { border-top:1px solid #1e293b; padding-top:12px; margin-top:8px; width:100%; }
    .admin-nav__logout { width:calc(100% - 28px); margin:4px 14px; padding:10px 14px; text-align:center; }
}
</style>

<script>
(function(){
    const burger  = document.getElementById('adminBurger');
    const menu    = document.getElementById('adminNavMenu');
    const overlay = document.getElementById('adminOverlay');
    if (!burger) return;
    function open()  { menu.classList.add('open'); burger.classList.add('open'); overlay.classList.add('show'); burger.setAttribute('aria-expanded','true'); document.body.style.overflow='hidden'; }
    function close() { menu.classList.remove('open'); burger.classList.remove('open'); overlay.classList.remove('show'); burger.setAttribute('aria-expanded','false'); document.body.style.overflow=''; }
    burger.addEventListener('click', () => menu.classList.contains('open') ? close() : open());
    overlay.addEventListener('click', close);
    document.addEventListener('keydown', e => { if(e.key==='Escape') close(); });
    menu.querySelectorAll('a').forEach(a => a.addEventListener('click', close));
})();
</script>
