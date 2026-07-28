<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationDetailsMail;

/**
 * Handles new user registration; the first user becomes admin.
 */
class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): \Illuminate\View\View
    {
        $programmes = Programme::orderBy('name')->get(['id', 'name', 'slug']);
        return view('auth.register', compact('programmes'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'   => 'required|string|max:255',
            'middle_name'  => 'nullable|string|max:255',
            'last_name'    => 'required|string|max:255',
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
            'email'        => 'required|string|lowercase|email|max:255|unique:'.User::class,
            'matric_no'    => 'required|numeric|unique:'.User::class,
            'programme_id' => 'required|exists:programmes,id',
        ]);

        $user = User::create([
            'first_name'   => $request->first_name,
            'middle_name'  => $request->middle_name,
            'last_name'    => $request->last_name,
            'email'        => $request->email,
            'matric_no'    => $request->matric_no,
            'programme_id' => $request->programme_id,
            'password'     => Hash::make($request->password),
            'role'         => User::count() <= 0 ? 'admin' : 'user',
        ]);

        event(new Registered($user));

        Mail::to($user->email)->send(new RegistrationDetailsMail($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
