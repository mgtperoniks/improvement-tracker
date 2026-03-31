<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'spv')->orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users',
            'employee_id' => 'required|string|max:50|unique:users',
            'department_name' => 'required|string|max:255',
            'position' => ['required', Rule::in(['Manajer', 'Kabag', 'SPV', 'Wakil SPV', 'Staff'])],
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        }
        
        $validated['role'] = 'spv';

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'employee_id' => ['required', 'string', 'max:50', Rule::unique('users')->ignore($user->id)],
            'department_name' => 'required|string|max:255',
            'position' => ['required', Rule::in(['Manajer', 'Kabag', 'SPV', 'Wakil SPV', 'Staff'])],
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'SPV deleted successfully.');
    }
}
