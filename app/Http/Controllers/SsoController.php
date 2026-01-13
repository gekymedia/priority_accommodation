<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SsoController extends Controller
{
    /**
     * Handle SSO login from CUG Admissions Portal
     * Route: /sso/login
     */
    public function login(Request $request)
    {
        try {
            $token = $request->query('token');
            $appId = $request->query('app_id');
            $email = $request->query('email');
            $name = $request->query('name');

            if (!$token) {
                Log::warning('SSO login attempt without token');
                return redirect()->route('login')->with('error', 'Invalid SSO token. Please try logging in manually.');
            }

            // Decode the token
            $decoded = json_decode(base64_decode($token), true);

            if (!$decoded || !isset($decoded['data']) || !isset($decoded['token']) || !isset($decoded['expires_at'])) {
                Log::warning('SSO login attempt with invalid token format', ['token' => substr($token, 0, 50)]);
                return redirect()->route('login')->with('error', 'Invalid SSO token format.');
            }

            // Verify expiration
            if ($decoded['expires_at'] < time()) {
                Log::warning('SSO login attempt with expired token', [
                    'expires_at' => $decoded['expires_at'],
                    'current_time' => time(),
                ]);
                return redirect()->route('login')->with('error', 'SSO token has expired. Please try again from the CUG portal.');
            }

            // Verify HMAC signature
            $secretKey = config('app.key');
            $userData = $decoded['data'];
            $expectedToken = hash_hmac('sha256', json_encode($userData), $secretKey);

            if (!hash_equals($expectedToken, $decoded['token'])) {
                Log::error('SSO login attempt with invalid signature', [
                    'app_id' => $userData['app_id'] ?? null,
                ]);
                return redirect()->route('login')->with('error', 'Invalid SSO signature. Please contact support.');
            }

            // Extract user data
            $appId = $userData['app_id'] ?? $appId;
            $email = $userData['email'] ?? $email;
            $firstName = $userData['first_name'] ?? null;
            $surname = $userData['surname'] ?? null;
            $middleName = $userData['middle_name'] ?? null;
            $phone = $userData['phone'] ?? null;
            $fullName = trim(($firstName ?? '') . ' ' . ($surname ?? '')) ?: $name;

            if (!$appId && !$email) {
                Log::error('SSO login attempt without app_id or email');
                return redirect()->route('login')->with('error', 'Missing user identification. Please contact support.');
            }

            // Find or create user
            $user = null;
            
            // First, try to find by app_id if we have it
            if ($appId) {
                $user = User::where('app_id', $appId)->first();
            }
            
            // If not found, try by email
            if (!$user && $email) {
                $user = User::where('email', $email)->first();
            }

            // Create user if doesn't exist
            if (!$user) {
                // Generate a random password (user won't need it for SSO logins)
                $password = Hash::make(Str::random(32));
                
                $user = User::create([
                    'name' => $fullName ?: 'CUG Applicant',
                    'email' => $email ?: "cug_{$appId}@example.com",
                    'password' => $password,
                    'app_id' => $appId,
                    'phone' => $phone,
                    'role' => 'student', // Default role for CUG applicants
                    'is_cug_verified' => true,
                    'cug_verified_at' => now(),
                    'profile_complete' => true, // CUG verified users have complete profiles
                ]);

                Log::info('SSO: Created new user from CUG', [
                    'user_id' => $user->id,
                    'app_id' => $appId,
                    'email' => $email,
                ]);
            } else {
                // Update existing user with CUG data
                $updateData = [];
                
                if ($appId && !$user->app_id) {
                    $updateData['app_id'] = $appId;
                }
                
                if ($fullName && $user->name !== $fullName) {
                    $updateData['name'] = $fullName;
                }
                
                if ($phone && !$user->phone) {
                    $updateData['phone'] = $phone;
                }
                
                if (!$user->is_cug_verified) {
                    $updateData['is_cug_verified'] = true;
                    $updateData['cug_verified_at'] = now();
                    $updateData['profile_complete'] = true;
                }
                
                if (!empty($updateData)) {
                    $user->update($updateData);
                    Log::info('SSO: Updated existing user with CUG data', [
                        'user_id' => $user->id,
                        'updates' => array_keys($updateData),
                    ]);
                }
            }

            // Log the user in
            Auth::login($user, true); // Remember the user
            
            // Regenerate session to prevent session fixation
            $request->session()->regenerate();

            Log::info('SSO: User successfully logged in', [
                'user_id' => $user->id,
                'app_id' => $appId,
                'email' => $user->email,
            ]);

            // Redirect based on user role
            $isAdmin = $user->hasRole(['admin', 'super_admin']) || 
                      in_array($user->role, ['admin', 'super_admin']);

            if ($isAdmin) {
                return redirect()->route('admin.dashboard')->with('success', 'Welcome! You have been automatically logged in from CUG Admissions Portal.');
            }

            return redirect()->route('user.dashboard')->with('success', 'Welcome! You have been automatically logged in from CUG Admissions Portal.');

        } catch (\Exception $e) {
            Log::error('SSO login error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('login')->with('error', 'An error occurred during SSO login. Please try logging in manually.');
        }
    }
}
