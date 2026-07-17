<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

/**
 * Handles user profile viewing, updating, and account deletion.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user'            => $request->user(),
            'status'          => session('status'),
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'emailInvalid'    => $request->user()->email_invalid ?? false,
            'emailInvalidReason' => $request->user()->email_invalid_reason ?? null,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
            // Reset email invalid flag when email is updated
            $request->user()->email_invalid = false;
            $request->user()->email_invalid_reason = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->isAdmin() && \App\Models\User::where('role', 'admin')->count() === 1) {
            return back()->with('error', 'You are the only admin. Promote another user to admin before deleting your account.');
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
