<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Undocumented function
     *
     * @return void
     */
    public function index()
    {
        // Fetch all users with their ticket count
        $users = User::withCount('tickets')->latest()->get();

        return Inertia::render('Admin/Users', [
            'users' => $users,
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Request $request
     * @param User $user
     * @return void
     */
    public function updateRole(Request $request, User $user)
    {
        // Prevent self-demotion
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => 'required|string|in:admin,user',
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', "Role for {$user->name} updated to {$validated['role']}.");
    }
}
