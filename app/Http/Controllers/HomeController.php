<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Setting;

class HomeController extends Controller
{
    public function index()
    {
        $settings = Setting::first();
        $wfee = $settings->withdrawal_fee;
        $rewardThreshold = $settings->reward_threshold;
        if (Auth::check()) {
            $auth = Auth::user();
            if ($auth->is_admin == 1) {
                return redirect('/admin/dashboard');
            } else {
                return redirect('/dashboard');
            }
        } else {
            return view('index', compact('wfee', 'rewardThreshold'));
        }
    }

    public function userIndex()
    {
        $settings = Setting::first();
        $wfee = $settings->withdrawal_fee;
        $rewardThreshold = $settings->reward_threshold;
        return view('index', compact('wfee', 'rewardThreshold'));
    }

    public function getUser()
    {
        if (Auth::check()) {
            $user = Auth::user();
            return response()->json(['user' => $user]);
        } else {
            $user['is_admin'] = 1;
            return response()->json(['user' => $user]);
        }
    }

    public function downloadApk()
    {
        $file = public_path() . "/download/sol.apk";
        $name = 'sol.apk';
        $headers = [
            'Content-Type'=>'application/vnd.android.package-archive',
            'Content-Disposition'=> 'attachment; filename="android.apk"',
        ];
        return response()->download($file, $name, $headers);
    }
}