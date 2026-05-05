<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Models\Role;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => ['required', 'confirmed', Password::min(6)],
            'birthdate'  => 'required|date|before:today',
            'age'        => 'nullable|integer|min:16|max:100',
            'gender'     => 'required|in:male,female',
            'city'       => 'required|string|max:100',
            'avatar'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'password'   => Hash::make($validated['password']),
            'birthdate'  => $validated['birthdate'],
            'age'        => $validated['age'] ?? null,
            'gender'     => $validated['gender'],
            'city'       => $validated['city'],
            'avatar'     => $avatarPath,
        ]);

        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $user->roles()->attach($userRole);
        }

        auth()->login($user);

        return redirect()->route('home')
                         ->with('success', 'Регистрация прошла успешно! Добро пожаловать в EventMaster!');
    }
}