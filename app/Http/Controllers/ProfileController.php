<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            // Store with unique name
            $imageName = 'profile-' . $user->id . '-' . time() . '.' . $request->file('profile_picture')->getClientOriginalExtension();
            $imagePath = $request->file('profile_picture')->storeAs('profile-pictures', $imageName, 'public');
            $validated['profile_picture'] = $imagePath;
            
            \Log::info('Profile picture stored at: ' . $imagePath);
        } else {
            // Keep the existing profile picture if no new one is uploaded
            unset($validated['profile_picture']);
        }

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Profile information updated successfully.');
    }

    public function updatePreferences(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'timezone' => 'required|string|max:50',
            'language' => 'required|string|max:10',
            'notifications' => 'required|boolean',
        ]);

        // Convert checkbox value to boolean
        $validated['notifications'] = (bool) $request->notifications;

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Account preferences updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.profile.edit')->with('success', 'Password updated successfully.');
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'email_notifications' => 'sometimes|array',
            'email_notifications.*' => 'string',
            'sms_notifications' => 'sometimes|array',
            'sms_notifications.*' => 'string',
        ]);

        // Handle empty arrays
        $validated['email_notifications'] = $request->email_notifications ?? [];
        $validated['sms_notifications'] = $request->sms_notifications ?? [];

        $user->update($validated);

        return redirect()->route('admin.profile.edit')->with('success', 'Notification preferences updated successfully.');
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Delete profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}