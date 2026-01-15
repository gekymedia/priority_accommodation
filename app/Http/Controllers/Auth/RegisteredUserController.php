<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'regex:/^0[235][0-9]{8}$/'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'terms' => ['required', 'accepted'],
        ], [
            'phone.regex' => 'Please enter a valid Ghana phone number (e.g., 0241234567)',
        ]);

        // Combine first and last name for the name field
        $fullName = trim($request->first_name . ' ' . $request->last_name);

        $user = User::create([
            'name' => $fullName,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            // Set Ghana-specific defaults
            'country' => 'ghana',
            'timezone' => 'Africa/Accra',
            'notifications' => true,
            'language' => 'en',
        ]);

        // Assign 'user' role using Spatie Permission
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            $userRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'user']);
            $user->assignRole($userRole);
        }

        event(new Registered($user));

        Auth::login($user);

        // Redirect to user dashboard, not admin
        return redirect(route('user.dashboard', absolute: false));
    }
}
