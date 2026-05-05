<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Role;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $eventsCount     = Event::count();
        $usersCount      = User::count();
        $categoriesCount = Category::count();
        $events          = Event::with('category', 'organizer')->latest()->get();

        return view('admin.index', compact('eventsCount', 'usersCount', 'categoriesCount', 'events'));
    }

    public function users()
    {
        $users = User::with('roles')->get();
        return view('admin.users', compact('users'));
    }

    public function toggleBlock(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Нельзя заблокировать себя.');
        }

        if ($user->isSuperAdmin() && !auth()->user()->isSuperAdmin()) {
            return back()->with('error', 'Нельзя заблокировать супер-администратора.');
        }

        $user->is_blocked = !$user->is_blocked;
        $user->save();

        $msg = $user->is_blocked ? 'Пользователь заблокирован.' : 'Пользователь разблокирован.';
        return back()->with('success', $msg);
    }

    public function eventParticipants(Event $event)
    {
        $participants = $event->registeredUsers()->get();
        return view('admin.participants', compact('event', 'participants'));
    }

    public function categories()
    {
        $categories = Category::withCount('events')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:categories,name']);
        Category::create(['name' => $request->name, 'slug' => \Illuminate\Support\Str::slug($request->name) . '-' . time()]);
        return back()->with('success', 'Категория добавлена.');
    }

    public function destroyCategory(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Категория удалена.');
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate(['role' => 'required|exists:roles,name']);

        $role = Role::where('name', $request->role)->firstOrFail();
        $user->roles()->sync([$role->id]);

        return back()->with('success', "Роль «{$role->display_name}» назначена пользователю {$user->name}.");
    }
}