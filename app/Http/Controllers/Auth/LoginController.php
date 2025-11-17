<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
class LoginController extends Controller
{
    public function login()
    {
        return view('auth.login', [
            'title' => 'Login',
        ]);
    }
    public function loginPost(Request $request)
    {
        try {
            $request->validate([
                'email'=>'required|email|max:50',
                'password'=>'required|max:50',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // If AJAX request, return JSON response for validation errors
            if($request->ajax() || $request->wantsJson()){
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
        
        if(Auth::attempt($request->only('email', 'password'))){
            $request->session()->regenerate();
            
            // Determine redirect URL based on role
            $redirectUrl = route('home');
            if($request->user()->role == 'admin'){
                $redirectUrl = route('admin.dashboard');
            }elseif($request->user()->role == 'instructor'){
                $redirectUrl = route('instructor.dashboard');
            }elseif($request->user()->role == 'user' || $request->user()->role == 'student'){
                $redirectUrl = route('home');
            }
            
            // If AJAX request, return JSON response
            if($request->ajax() || $request->wantsJson()){
                return response()->json([
                    'success' => true,
                    'message' => 'Login berhasil!',
                    'redirect_url' => $redirectUrl
                ]);
            }
            
            return redirect()->intended($redirectUrl);
        }
        
        // If AJAX request, return JSON response for error
        if($request->ajax() || $request->wantsJson()){
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah!'
            ], 401);
        }
        
        return back()->with('loginError', 'Login failed! ');
        
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
    }
}
