<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.pages.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Handle file uploads
        if ($request->hasFile('shop_header_image')) {
            $path = $request->file('shop_header_image')->store('settings', 'public');
            $data['shop_header_image'] = $path;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Invalidate Shiprocket auth token cache in case credentials were changed
        Cache::forget('shiprocket_auth_token');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
