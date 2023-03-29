<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Bank;
use App\Models\Banner;
use App\Models\Order;
use Illuminate\Support\Facades\Hash;
use Auth;
use Intervention\Image\Facades\Image as Image;
use Carbon\Carbon;


class AdminController extends Controller
{
    public function Users(Request $request)
    {
        $itemsPerPage = $request->itemsPerPage;
        $date = \Carbon\Carbon::today()->subDays(30);
        // $active_user_id = Order::where('created_at', '>=', $date)->pluck('user_id');
        // $inactive_user_id = Order::where('created_at', '<=', $date)->pluck('user_id');


        // return response()->json($inactive_user_id);
        if ($request->isSearch == true) {
            $searchUser = $request->searchUser;
            $users = User::where('phone', 'LIKE', "%$searchUser%")->with('banks', 'addresses')->latest('id')->paginate($itemsPerPage);            
        } else {
            if ($request->type == 'blocked') {
                $users = User::with('banks', 'addresses')->where('is_blocked', 1)->latest('id')->paginate($itemsPerPage);            
            } else if ($request->type == 'active') {
                // $users = User::with('banks', 'addresses')->whereIn('id', $active_user_id->toArray())->where('is_blocked', 0)->latest('id')->paginate($itemsPerPage);
                $users = User::with('banks', 'addresses')->where('created_at', '>=', $date)->where('is_blocked', 0)->latest('id')->paginate($itemsPerPage);

            } else if ($request->type == 'inactive') {
                // $users = User::with('banks', 'addresses')->whereIn('id', $inactive_user_id->toArray())->where('is_blocked', 0)->latest('id')->paginate($itemsPerPage);
                $users = User::with('banks', 'addresses')->where('created_at', '>=', $date)->where('is_blocked', 0)->latest('id')->paginate($itemsPerPage);
                
            }
            return response()->json(['users' => $users]);
        };
    }

    public function getUser($user_id)
    {
        $user = User::find($user_id);
        return response()->json(['user' => $user]);
    }

    public function userBlock(Request $request)
    {
        $auth = Auth::user();
        $data = User::where('id', $request->id)->first();
        if ($auth->is_admin == 1) {
            if ($data != null) {
                if ($data->is_blocked == 0) {
                    $data->update(['is_blocked' => 1]);
                    return response()->json(['success' => 'User Blocked Successfully.']);
              } else {
                    $data->update(['is_blocked' => 0]);                
                    return response()->json(['success' => 'User Unblock Successfully!!']);
              }
            }
            return response()->json(['error' => 'Please try again.']);
        }
        return response()->json(['error' => 'You are not admin!!']);
    }

    public function changeUserPassword(Request $request)
    {
        $auth = Auth::user();
        $password = $request->password;
        $id = $request->id;
        if ($auth->is_admin == 1) {
            $user = User::where('id', $id)->first();
            if ($user != null) {
                if ($password == $user->password_text) {
                    return response()->json(['error' => "Old password does not accepted !"]);
                }
                if ($password == null && $password == '') {
                    return response()->json(['error' => "Invalid password !"]);
                }
                $user->update([
                    'password' => Hash::make($password),
                    'password_text' => $password,
                ]);
                return response()->json(['success' => 'Password changed successfully']); 
            }           
        }
    }
    public function updateHomeBanner(Request $request)
    {
        $input = Banner::first();
        $home = $input->home;
        if($request->home != $home){
            $home_image = time().'.' . explode('/', explode(':', substr($request->home, 0, strpos($request->home, ';')))[1])[1];
            Image::make($request->home)->save(public_path() . '/images/poster/' . $home_image);
            $request->merge(['home' => $home_image]);
            if ($home == null OR $home == '') {
                $input['home'] = $request->home;
                
            } else {
                unlink(public_path() . '/images/poster/' . $home);
            }
        }

        $input->update([
            'home' => $request->home
        ]);
        return response()->json(['success' => 'banner update  Successfully !']);
        // $permotion = $input->permotion;
        // if($request->permotion != $permotion){
        //     $permotion_image = time().'.' . explode('/', explode(':', substr($request->permotion, 0, strpos($request->permotion, ';')))[1])[1];
        //     Image::make($request->permotion)->save(public_path() . '/images/poster/' . $permotion_image);
        //     $request->merge(['permotion' => $permotion_image]);
        //     if ($permotion == null OR $permotion == '') {
        //         $input['permotion'] = $request->permotion;
        //     } else {
        //         unlink(public_path() . '/images/poster/' . $permotion);
        //     }
        // }
        // $dw = $input->dw;
        // if($request->dw != $dw){
        //     $dw_image = time().'.' . explode('/', explode(':', substr($request->dw, 0, strpos($request->dw, ';')))[1])[1];
        //     Image::make($request->dw)->save(public_path() . '/images/poster/' . $dw_image);
        //     $request->merge(['dw' => $dw_image]);
        //     if ($dw == null OR $dw == '') {
        //         $input['dw'] = $request->dw;
        //     } else {
        //         unlink(public_path() . '/images/poster/' . $dw);
        //     }
        // }

    }
    public function updatePromotionBanner(Request $request)
    {
        $input = Banner::first();        
        $permotion = $input->permotion;
        if($request->permotion != $permotion){
            $permotion_image = time().'.' . explode('/', explode(':', substr($request->permotion, 0, strpos($request->permotion, ';')))[1])[1];
            Image::make($request->permotion)->save(public_path() . '/images/poster/' . $permotion_image);
            $request->merge(['permotion' => $permotion_image]);
            if ($permotion == null OR $permotion == '') {
                $input['permotion'] = $request->permotion;
            } else {
                unlink(public_path() . '/images/poster/' . $permotion);
            }
        }
        $input->update([
            'permotion' => $request->permotion
        ]);
        return response()->json(['success' => 'banner update  Successfully !']);
    }
    public function updateDwBanner(Request $request)
    {
        $input = Banner::first();        
        $dw = $input->dw;
        if($request->dw != $dw){
            $dw_image = time().'.' . explode('/', explode(':', substr($request->dw, 0, strpos($request->dw, ';')))[1])[1];
            Image::make($request->dw)->save(public_path() . '/images/poster/' . $dw_image);
            $request->merge(['dw' => $dw_image]);
            if ($dw == null OR $dw == '') {
                $input['dw'] = $request->dw;
            } else {
                unlink(public_path() . '/images/poster/' . $dw);
            }
        }
        $input->update([
            'dw' => $request->dw
        ]);
        return response()->json(['success' => 'banner update  Successfully !']);
    }
    public function getBanner()
    {
        $banner = Banner::first();
        return response()->json(['banner' => $banner]);
    }

    public function getUserLevels($user_id)
    {
        $user = User::find($user_id);
        $one_levels = User::where('referred_id', $user->id)->get(["level_1_first_reward AS first_reward", "level_1_water_reward AS water_reward", "id", "phone", "contribution"]);
        $two_levels = User::whereIn('referred_id', $one_levels->pluck('id')->toArray())->get(["level_2_first_reward AS first_reward", "level_2_water_reward AS water_reward", "id", "phone", "contribution"]);
        $three_levels = User::whereIn('referred_id', $two_levels->pluck('id')->toArray())->get(["level_3_first_reward AS first_reward", "level_3_water_reward AS water_reward", "id", "phone", "contribution"]);
        $total_people = $one_levels->count() + $two_levels->count() + $three_levels->count();
        $contribution = $one_levels->sum('contribution') + $two_levels->sum('contribution') + $three_levels->sum('contribution');
        return response()->json(['one_levels' => $one_levels, 'two_levels' => $two_levels, 'three_levels' => $three_levels, 'total_people' => $total_people, 'contribution' => $contribution]);
    }
    public function DepositAmount(Request $request)
    {
        $id = $request->id;
        $amount = $request->amount;
        $user = User::where('id', $id)->first();
        if ($user) {
            $user->update([
                'balance' => $user->balance + $amount
            ]);
            return response()->json(['success' => 'Deposit amount add Successfully !']);
        } else {
            return response()->json(['error' => 'Please try again']);
        }
    }
    public function WithdrawalAmount(Request $request)
    {
        $id = $request->id;
        $type = $request->type;
        $amount = $request->amount;
        $user = User::where('id', $id)->first();
        if ($type == 'primary') {
            if ($user->balance > 0 && $user->balance > $amount) {
                $user->update([
                    'balance' => $user->balance - $amount
                ]);
                return response()->json(['success' => 'Withdrawal Successfully !']);
            } else {
                return response()->json(['error' => 'User have not enough balance to withdrawal ']);
            }
        } else if ($type == 'secondary') {
            if ($user->loss_reward_balance > 0 && $user->loss_reward_balance > $amount) {
                $user->update([
                    'loss_reward_balance' => $user->loss_reward_balance - $amount
                ]);
                return response()->json(['success' => 'Withdrawal Successfully !']);
            } else {
                return response()->json(['error' => 'User have not enough secondary balance to withdrawal ']);
            }
        }
    }
}
