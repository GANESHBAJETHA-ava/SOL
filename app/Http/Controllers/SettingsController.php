<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function getSettings()
    {
        $settings = Setting::first();
        if ($settings == null) {
            $settings = Setting::create([
                'first_reward' => 15,
                'level_1_reward' => 30,
                'level_2_reward' => 20,
                'level_3_reward' => 10,
                'order_fee' => 5,
                'withdrawal_fee' => 5
            ]);
        }
        return response()->json(['settings' => $settings]);
    }

    public function saveSettings(Request $request)
    {
        $settings_data = $request->settings;
        $settings = Setting::first();
        $settings->update($settings_data);
        return;
    }
}
