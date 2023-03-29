<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\Events\LogoutEvent;
use App\Models\User;
use Auth;
use Carbon\Carbon;


class UserAuthController extends Controller
{
    public function getLogin()
    {
        return view('login');
    }

    public function getRegister()
    {
        return view('register');
    }
    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function signUp(Request $request)
    {
        $data = $request->data;
        $otp = $request->otp;

        $r_code = isset($data["r_code"]) ? $data["r_code"] : "";
        $validator = Validator::make($data, [
            'name' => ['required'],
            'phone' => ['required'],
            'password' => ['required', 'min:6'],
        ]);

        $check_user = User::where('phone', $data['phone'])->first();
        if ($check_user != null) {
            return response()->json(['error' => 'Phone number already registered']);
        }

        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }

        //  Check number
        if ($data['phone'] != session()->get('phone_number_for_otp')) {
            return response()->json(['error' => 'Phone number for otp is invalid']);
        }

        if ((string) $otp === (string) session()->get('verification_code_otp')) {
            $code_invalid = true;
            $code = "";
            while ($code_invalid) {
                $str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $code = substr(str_shuffle($str_result), 0, 8);
                $user_r_code = User::where('r_code', $code)->first();
                if ($user_r_code == null) {
                    $code_invalid = false;
                }
            }

            // REFERRENCE CONFIG
            $parent_id = null;
            if ($r_code != null && $r_code != "") {
                $main_parent = User::where('r_code', $r_code)->first();
                if ($main_parent != null) {
                    $parent_id = $main_parent->id;
                }
            }

            $user = User::create([
                'r_code' => $code,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'password' => Hash::make($data['password']),
                'referred_id' => $parent_id,
                'password_text' => $data['password'],
            ]);

            session()->forget('verification_code_otp');
            session()->forget('phone_number_for_otp');
            session()->forget('otp_timer');
            Auth::login($user);
            return response()->json(['success' => 'Signed up successfully']);
        }  else {
            return response()->json(['error' => 'Verification code is invalid']);
        }
    }

    public function login(Request $request)
    {
        $creds = $request->only(['phone', 'password']);
        $view = $request->view;
        $user = User::where('phone', $request->phone)->first();
        if ($user != null && $user->is_blocked == true) {
            return response()->json(['error' => 'Username or password invalid']);
        }
        if (Auth::attempt($creds)) {
            $request->session()->regenerate();
            $auth = Auth::user();
            $user = User::where(["phone" => $creds['phone']])->first();
            Auth::login($user);
            return response()->json(['success' => 'You are logged in successfully', 'user' => $auth]);
        } else {
            return response()->json(['error' => 'Username or password invalid']);
        }
    }
    
    public function UserLogout(Request $request)
    {
        Session::flush();
        Auth::logout();
        return response()->json(['success' => "Logged out successfully"]);
    }
    public function checkPhone(Request $request)
    {
        $valid = 1;
        $check = User::where('phone', $request->phone)->first();
        if ($check) {
            $valid = 0;
            return response()->json(['valid' => $valid]);
        } else {
            return response()->json(['valid' => $valid]);
        }
    }

    public function sendOtp(Request $request)
    {
        session()->forget('verification_code_otp');
        session()->forget('phone_number_for_otp');
        session()->forget('otp_timer');
        $is_otp_timer_verified = 0;
        $otp_timer = session()->get('otp_timer');
        if ($otp_timer != null && $otp_timer != '') {
            $now = Carbon::now();
            if ($now->diffInSeconds($otp_timer) > 25) {
                $is_otp_timer_verified = 1;
                session()->put('otp_timer', Carbon::now());
            }
        } else {
            $is_otp_timer_verified = 1;
            session()->put('otp_timer', Carbon::now());
        }
        if ($is_otp_timer_verified) {
            session()->forget('verification_code_otp');
            session()->forget('phone_number_for_otp');
            $phone = $request->phone;
            $code = (string) (random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9) . random_int(0, 9));
            session()->put('verification_code_otp', $code);
            session()->put('phone_number_for_otp', $phone);
            $auth_token = getenv("FACTOR_API_KEY");
            $recipients = "+91" . $phone;
            $pcexpert_url = 'https://2factor.in/API/V1/'. $auth_token .'/SMS/'. $recipients . '/' . $code;
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $pcexpert_url, [
                'headers' => [
                    'authToken' => env('auth_token'),
                ]
            ]);
            $response = $response->getBody()->getContents();
        }
        return;
    }
    public function verifyOtp($phone, $otp)
    {
        $is_verified = 0;
        $real_otp = session()->get('verification_code_otp');
        $real_phone_for_otp = session()->get('phone_number_for_otp');
        if ($otp == $real_otp && $phone == $real_phone_for_otp) {
            $is_verified = 1;
        }
        return response()->json(['is_verified' => $is_verified]);
    }
    public function checkPhoneNumber($phone)
    {
        $check_user = User::where('phone', $phone)->first();
        if ($check_user != null) {
            return response()->json(['success' => 'Phone number is registered.']);
        } else {
            return response()->json(['error' => 'Phone number is not registered.']);
        }
    }

     public function changePasswordAfterOtp(Request $request)
    {
        $data = $request->password;
        $phone = $request->phone;
        $otp = $request->otp;

        $validator = Validator::make($data, [
            'password' => ['required', 'min:8'],
        ]);

        // $validator = $request->validate([
        //     'password' => ['required', 'min:8'],
        // ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Please re-check the filled data']);
        }

        //  Check number
        if ($phone != session()->get('phone_number_for_otp')) {
            return response()->json(['error' => 'Phone number is invalid']);
        }

        if ((string) $otp === (string) session()->get('verification_code_otp')) {
            $user = User::where('phone', $phone)->first();
            if ($user) {
                $user->update([
                    'password_text' => trim($data['password']),
                    'password' => Hash::make(trim($data['password'])),
                ]);
                session()->forget('verification_code_otp');
                session()->forget('phone_number_for_otp');
                session()->forget('otp_timer');
                return response()->json(['success' => 'Password has been updated successfully.']);
            } else {
                return response()->json(['error' => 'Invalid user']);
            }
        } else {
            return response()->json(['error' => 'Verification code is invalid']);
        }
    }
}
