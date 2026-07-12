<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get all settings as key-value pairs
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return response()->json($settings);
    }

    /**
     * Bulk update settings
     */
    public function update(Request $request)
    {
        $data = $request->except(['payment_qris_image']);
        
        // Handle normal text fields
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Handle file upload for QRIS Image
        if ($request->hasFile('payment_qris_image')) {
            $file = $request->file('payment_qris_image');
            $path = $file->store('settings', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'payment_qris_image'],
                ['value' => '/storage/' . $path]
            );
        }

        return response()->json([
            'message' => 'Settings updated successfully'
        ]);
    }
}
