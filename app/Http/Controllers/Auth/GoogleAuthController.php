<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Exception;

class GoogleAuthController extends Controller
{
    /**
     * Redirect to Google OAuth provider
     */
    public function redirectToGoogle()
    {
        try {
            // Check if Google OAuth is configured
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi. Silakan hubungi administrator atau cek file .env');
            }

            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email'])
                ->redirect();
        } catch (\Exception $e) {
            \Log::error('Google OAuth Redirect Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal mengarahkan ke Google. Error: ' . $e->getMessage());
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback()
    {
        try {
            // Check if Google OAuth is configured
            if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
                return redirect()->route('login')->with('error', 'Google OAuth belum dikonfigurasi. Silakan cek file .env');
            }

            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser || !$googleUser->email) {
                return redirect()->route('login')->with('error', 'Gagal mendapatkan informasi dari Google. Silakan coba lagi.');
            }

            // Check if user exists with this email
            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->google_id = $googleUser->id;
                    $user->save();
                }
                
                // Update email_verified_at if not set
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->name ?? $googleUser->email,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(16)), // Random password for Google-only users
                    'role' => 'student', // Default role
                    'status' => 'active',
                    'email_verified_at' => now(),
                ]);
            }

            // Login the user
            Auth::login($user, true);

            // Redirect based on role
            if ($user->role == 'admin') {
                return redirect()->intended('admin/dashboard');
            } elseif ($user->role == 'instructor') {
                return redirect()->intended('instructor/dashboard');
            } elseif ($user->role == 'user' || $user->role == 'student') {
                return redirect()->intended('student/dashboard');
            }

            return redirect()->route('home');
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            \Log::error('Google OAuth Connection Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Tidak dapat terhubung ke Google. Pastikan koneksi internet Anda aktif.');
        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {
            \Log::error('Google OAuth Invalid State: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Session expired. Silakan coba lagi.');
        } catch (\Exception $e) {
            \Log::error('Google OAuth Callback Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Show detailed error in development, generic in production
            $errorMessage = app()->environment('local') 
                ? 'Error: ' . $e->getMessage() 
                : 'Gagal autentikasi dengan Google. Pastikan Google OAuth sudah dikonfigurasi dengan benar.';
                
            return redirect()->route('login')->with('error', $errorMessage);
        }
    }
}

