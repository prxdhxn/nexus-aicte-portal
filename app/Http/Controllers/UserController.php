<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,sme,institute',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    public function profile(User $user)
    {
        abort_if($user->role !== 'institute', 404);
        
        $user->load('adoptions.curriculum');
        
        $stats = [
            'total_adoptions' => $user->adoptions->count(),
            'avg_score' => round($user->adoptions->whereNotNull('approval_score')->avg('approval_score') ?? 0, 1),
            'pending_reviews' => $user->adoptions->whereNull('approval_score')->count(),
        ];

        return view('institutes.profile', compact('user', 'stats'));
    }
}
