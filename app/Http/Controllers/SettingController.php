<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index()
    {
        $settings = DB::table('settings')->pluck('value', 'key')->all();
        
        // Define default values for settings that don't exist in the database
        $defaultSettings = [
            'social_facebook' => '',
            'social_twitter' => '',
            'social_instagram' => '',
            'social_linkedin' => '',
            'contact_email' => '',
            'contact_phone' => '',
            'contact_address' => '',
            'about_us_content' => '',
            'store_name' => '',
            'business_type' => '',
            'opening_date' => '',
            'location_link' => '',
        ];
        
        // Merge default settings with existing settings
        $settings = array_merge($defaultSettings, $settings);
        
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $settingKeys = [
            'social_facebook' => 'nullable|url',
            'social_twitter' => 'nullable|url',
            'social_instagram' => 'nullable|url',
            'social_linkedin' => 'nullable|url',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'contact_address' => 'nullable|string',
            'about_us_content' => 'nullable|string',
            'store_name' => 'nullable|string',
            'business_type' => 'nullable|string',
            'opening_date' => 'nullable|string',
            'location_link' => 'nullable|url',
        ];

        $validator = Validator::make($request->all(), $settingKeys);

        if ($validator->fails()) {
            return redirect()->route('admin.settings.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        foreach ($settingKeys as $key => $rule) {
            $value = $request->input($key, null);
            
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'تم تحديث الإعدادات بنجاح.');
    }
}
