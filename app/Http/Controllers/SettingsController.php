<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => config('app.name', 'Hostel Management System'),
            'contact_email' => config('mail.from.address', 'admin@hostel.com'),
            'contact_phone' => Cache::get('contact_phone', '+1234567890'),
            'check_in_time' => Cache::get('check_in_time', '14:00'),
            'check_out_time' => Cache::get('check_out_time', '11:00'),
            'max_booking_days' => Cache::get('max_booking_days', 180),
            'cancellation_policy' => Cache::get('cancellation_policy', 'Free cancellation up to 24 hours before check-in'),
            'maintenance_mode' => Cache::get('maintenance_mode', false),
        ];

        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i',
            'max_booking_days' => 'required|integer|min:1|max:365',
            'cancellation_policy' => 'required|string|max:500',
            'maintenance_mode' => 'boolean',
        ]);

        // Store settings in cache (you can use database instead)
        foreach ($validated as $key => $value) {
            if ($key !== 'site_name') {
                Cache::forever($key, $value);
            }
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Settings updated successfully.');
    }
}