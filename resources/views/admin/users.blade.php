<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.admin_manage_users') }} | EventMaster Admin</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
</head>
<body>

@include('components.admin-navbar')

<main class="admin-main">
    <div class="admin-container">

        <h1 class="admin-title">{{ __('messages.admin_manage_users') }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        <div class="table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>{{ __('messages.admin_user_id') }}</th>
                        <th>{{ __('messages.admin_user_name') }}</th>
                        <th>{{ __('messages.admin_user_email') }}</th>
                        <th>{{ __('messages.admin_user_city') }}</th>
                        <th>{{ __('messages.admin_user_role') }}</th>
                        <th>{{ __('messages.admin_user_status') }}</th>
                        @if(auth()->user()->isSuperAdmin())
                            <th>{{ __('messages.admin_assign_role') }}</th>
                        @endif
                        <th>{{ __('messages.admin_actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr class="{{ $user->is_blocked ? 'row-blocked' : '' }}">
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->city ?? '—' }}</td>

                        <td>
                            @forelse($user->roles as $role)
                                <span class="badge badge-{{ $role->name }}">{{ $role->display_name }}</span>
                            @empty
                                <span class="badge badge-none">—</span>
                            @endforelse
                        </td>

                        <td>
                            @if($user->is_blocked)
                                <span class="badge badge-blocked">{{ __('messages.admin_blocked') }}</span>
                            @else
                                <span class="badge badge-active">{{ __('messages.admin_active') }}</span>
                            @endif
                        </td>

                        @if(auth()->user()->isSuperAdmin())
                        <td>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.assign-role', $user) }}" method="POST" class="assign-role-form">
                                @csrf
                                <select name="role" class="role-select">
                                    <option value="user"       {{ $user->hasRole('user')        ? 'selected' : '' }}>{{ __('messages.admin_users') }}</option>
                                    <option value="organizer"  {{ $user->hasRole('organizer')   ? 'selected' : '' }}>{{ __('messages.event_organizer') }}</option>
                                    <option value="admin"      {{ $user->hasRole('admin')       ? 'selected' : '' }}>{{ __('messages.admin_panel') }}</option>
                                    <option value="super_admin"{{ $user->hasRole('super_admin') ? 'selected' : '' }}>Super Admin</option>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">{{ __('messages.admin_save') }}</button>
                            </form>
                            @else
                                <span class="text-muted">{{ __('messages.admin_its_you') }}</span>
                            @endif
                        </td>
                        @endif

                        <td>
                            @if($user->id !== auth()->id())
                                @if(!($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()))
                                <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="btn btn-sm {{ $user->is_blocked ? 'btn-success' : 'btn-danger' }}"
                                            onclick="return confirm('{{ $user->is_blocked ? __('messages.admin_unblock') : __('messages.admin_block') }}?')">
                                        {{ $user->is_blocked ? __('messages.admin_unblock') : __('messages.admin_block') }}
                                    </button>
                                </form>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</main>

<style>
.admin-main { padding: 2rem; }
.admin-container { max-width: 1200px; margin: 0 auto; }
.admin-title { font-size: 1.8rem; margin-bottom: 1.5rem; color: #1e293b; }
.alert { padding: .75rem 1rem; border-radius: 6px; margin-bottom: 1rem; }
.alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
.alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }
.table-wrapper { overflow-x: auto; }
.admin-table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
.admin-table th { background: #1e293b; color: #fff; padding: .75rem 1rem; text-align: left; font-size: .85rem; }
.admin-table td { padding: .7rem 1rem; border-bottom: 1px solid #f1f5f9; font-size: .9rem; vertical-align: middle; }
.admin-table tr:last-child td { border-bottom: none; }
.row-blocked td { background: #fff5f5; }
.badge { display: inline-block; padding: .25rem .6rem; border-radius: 999px; font-size: .75rem; font-weight: 600; }
.badge-super_admin { background: #7c3aed; color: #fff; }
.badge-admin       { background: #2563eb; color: #fff; }
.badge-organizer   { background: #d97706; color: #fff; }
.badge-user        { background: #6b7280; color: #fff; }
.badge-none        { background: #e5e7eb; color: #374151; }
.badge-active      { background: #d1fae5; color: #065f46; }
.badge-blocked     { background: #fee2e2; color: #991b1b; }
.assign-role-form { display: flex; gap: .4rem; align-items: center; }
.role-select { padding: .3rem .5rem; border: 1px solid #cbd5e1; border-radius: 5px; font-size: .85rem; }
.btn { padding: .35rem .8rem; border: none; border-radius: 5px; cursor: pointer; font-size: .8rem; font-weight: 600; }
.btn-primary { background: #2563eb; color: #fff; }
.btn-danger  { background: #dc2626; color: #fff; }
.btn-success { background: #16a34a; color: #fff; }
.btn-sm { padding: .3rem .65rem; }
.btn:hover { opacity: .88; }
.text-muted { color: #9ca3af; font-size: .85rem; }
.nav-logout-btn { background: none; border: 1px solid #fff; color: #fff; padding: .3rem .7rem; border-radius: 5px; cursor: pointer; }
</style>

</body>
</html>
