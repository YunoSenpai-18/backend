<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    //Enforce Admin-only Actions
    private function authorizeAdmin($user)
    {
        if ($user->role !== 'Admin') {
            abort(403, 'Unauthorized action. Admins only.');
        }
    }

    // Get all users
    public function index()
    {
        return response()->json(User::all());
    }

    // Get single user
    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    // Create new user
    public function store(Request $request)
    {
        $this->authorizeAdmin($request->user());

        $validated = $request->validate([
            'full_name'   => 'required|string|max:255',
            'school_id'   => 'required|string|max:20|unique:users',
            'email'       => 'required|email|unique:users',
            'phone'       => 'nullable|string|max:15|unique:users',
            'role'        => 'required|in:Checker,Admin',
            'password'    => 'required|string|min:6',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $path;
        }

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

        return response()->json($user, 201);
    }

    // Update existing user
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin($request->user());

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'full_name'   => 'sometimes|string|max:255',
            'school_id'   => 'sometimes|string|max:20|unique:users,school_id,' . $user->id,
            'email'       => 'sometimes|email|unique:users,email,' . $user->id,
            'phone'       => 'nullable|string|max:15|unique:users,phone,' . $user->id,
            'role'        => 'sometimes|in:Checker,Admin',
            'password'    => 'nullable|string|min:6',
            'photo'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $path = $request->file('photo')->store('users', 'public');
            $validated['photo'] = $path;
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    // Delete user
    public function destroy(Request $request, $id)
    {
        $this->authorizeAdmin($request->user());

        $user = User::findOrFail($id);

        if ($user->photo && Storage::disk('public')->exists($user->photo)) {
            Storage::disk('public')->delete($user->photo);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
