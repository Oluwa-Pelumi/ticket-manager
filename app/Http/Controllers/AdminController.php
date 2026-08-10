<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Admin panel for managing users and their roles.
 */
class AdminController extends Controller
{
    /**
     * List all users with their ticket counts.
     */
    public function index()
    {
        // Fetch all users with their ticket count
        $users = User::withCount('tickets')->orderByDesc('id')->get();

        return view('admin.users', [
            'users' => $users,
        ]);
    }

    /**
     * Update a user's role (admin, support, or user).
     */
    public function updateRole(Request $request, User $user)
    {
        // Prevent self-demotion
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $validated = $request->validate([
            'role' => 'required|string|in:admin,user,support',
        ]);

        $user->update([
            'role' => $validated['role'],
        ]);

        return back()->with('success', "Role for {$user->name} updated to {$validated['role']}.");
    }

    /**
     * Delete a user.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if (Auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', "User {$user->name} deleted successfully.");
    }
}
