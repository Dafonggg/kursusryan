<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class SettingsController extends Controller
{
    /**
     * Display system settings page
     */
    public function index()
    {
        $settings = [
            'course_expiry_months' => config('app.course_expiry_months', 3),
            'logo_path' => config('app.logo_path'),
            'email_notifications_enabled' => config('app.email_notifications_enabled', true),
            'email_support' => config('app.email_support'),
            'site_name' => config('app.name'),
            'site_description' => config('app.description'),
            'maintenance_mode' => config('app.maintenance_mode', false),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update system settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'course_expiry_months' => 'required|integer|min:1|max:12',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'email_notifications_enabled' => 'nullable|boolean',
            'email_support' => 'nullable|email|max:255',
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'maintenance_mode' => 'nullable|boolean',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('settings', 'public');
            $validated['logo_path'] = $logoPath;
        }

        // Update config file or database (using cache for now)
        // In production, you might want to store these in database or .env file
        foreach ($validated as $key => $value) {
            if ($key !== 'logo') {
                Cache::forever("app_settings_{$key}", $value);
            }
        }

        if (isset($validated['logo_path'])) {
            Cache::forever('app_settings_logo_path', $validated['logo_path']);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan sistem berhasil diperbarui!');
    }

    /**
     * Get current settings (JSON API)
     */
    public function getSettings()
    {
        $settings = [
            'course_expiry_months' => config('app.course_expiry_months', 3),
            'logo_path' => config('app.logo_path'),
            'email_notifications_enabled' => config('app.email_notifications_enabled', true),
            'email_support' => config('app.email_support'),
        ];

        return response()->json($settings);
    }
}

