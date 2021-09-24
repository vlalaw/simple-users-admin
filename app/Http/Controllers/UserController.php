<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index')->with([
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return view('users.create')->with([
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:users,email',
            'role' => 'exists:roles,name',
        ]);

        $user = User::create($validated);
        $user->role()->associate(Role::byName($validated['role'])->first());
        $user->save();

        return redirect()->route('users.index')->with('message', 'User successfully created!');
    }

    public function edit(User $user)
    {
        return view('users.edit')->with([
            'user' => $user,
            'roles' => Role::all(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email:rfc|unique:users,email,'. $user->id.',id',
            'role' => 'exists:roles,name',
        ]);

        $user->fill($validated);
        $user->role()->associate(Role::byName($validated['role'])->first());
        $user->save();

        return redirect()->route('users.index')->with('message', "User id {$user->id} was edited.");
    }
}
