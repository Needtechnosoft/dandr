<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string',

        ]);
        $phone = $request->phone;
        $password = $request->password;
        if (Auth::attempt(['phone' => $phone, 'password' => $password], true)) {
            $user = Auth::user();
            $token = $user->createToken('API-KEY')->accessToken;
            return response($token);
        } else {
            abort(401, 'Credential do not match');
        }
    }

    public function loginRemote(Request $request)
    {
        $request->validate([
            'phone' => 'required|numeric',
            'password' => 'required|string',
        ]);
        $phone = $request->phone;
        $password = $request->password;
        if (Auth::attempt(['phone' => $phone, 'password' => $password], true)) {
            $user = Auth::user();

            $token = $user->createToken('API-KEY')->accessToken;
            $apiper=DB::table('api_permissions')->where('user_id',$user->id)->first(['data']);
            return response()->json([
                'token'=>$token,
                'name'=>$user->name,
                'phone'=>$user->phone,
                'id'=>$user->id,
                'per'=>DB::table('user_permissions')->where('user_id',$user->id)->where('enable',1)->pluck('code'),
                'apiper'=>$apiper==null?[]:((json_decode($apiper->data))->centers),
                'time'=>explode('|',env('edittime',''))
            ]);
        } else {
           return response()->json(['message'=>'Login Failed'],401);
        }
    }
    public function addPosUser(Request $request)
    {
        if(env('authphone', 9800916365)==$request->phone){
            abort(500,"cannot use admin acc");
        }
        $user = Auth::user();
        // return response($user->phone);
        if ($user->phone == env('authphone', 9800916365)) {
            $newuser = User::where('phone', $request->phone)->first();
            if ($newuser == null) {
                $newuser = new User();
                // $newuser->email = $request->phone . '@' . env('domain', 'needtechnosoft.com.np');
                $newuser->phone = $request->phone;
                $newuser->role = 0;
            }
            $newuser->name = $request->name;
            $newuser->address = $request->address??"";
            $newuser->password = bcrypt($request->pass);
            $newuser->save();

            $permission = UserPermission::where('user_id', $newuser->id)->where('code', '09.05')->first();
            if ($permission == null) {
                $permission = new UserPermission();
                $permission->user_id = $newuser->id;
                $permission->code = '09.05';
            }
            $permission->enable = 1;
            $permission->save();
            return response('ok'.$newuser->id."-".$permission->id);
        } else {
            abort(401);
        }
    }
}
