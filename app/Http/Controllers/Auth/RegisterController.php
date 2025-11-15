<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register()
    {
        return view('auth.register', [
            'title' => 'Register',
        ]);
    }

    public function registerPost(Request $request)
    {
        // Validasi data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'email_confirmation' => 'required|email|same:email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'email_confirmation.required' => 'Konfirmasi email wajib diisi.',
            'email_confirmation.same' => 'Konfirmasi email tidak cocok.',
            'phone.required' => 'Nomor handphone wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi kata sandi wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Buat user baru
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'student',
                'status' => 'active',
            ]);

            // Buat user profile
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => $request->phone,
            ]);

            // Login otomatis setelah registrasi
            Auth::login($user);

            // Redirect ke dashboard student
            return redirect()->route('student.dashboard')->with('success', 'Registrasi berhasil! Selamat datang, ' . $user->name . '!');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.')->withInput();
        }
    }
}
