<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmEmail;
use App\Mail\ResetPasswordEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;




class AuthController extends Controller
{
    /********
     * Login user and give auth_token
     *
     * @param Request $request
     * @return bool
     * @return array $user
     * @author Gordan
     *****
     */
    public function login(Request $request) {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user_t = User::find(Auth::id());
            $user_t -> auth_token = md5($request->email).Str::random(32);
            $user_t -> save();
            $user   = User::where('email',$request->email)->first();
            return $user->toJson();
        }
        return "False";
    }
    /********
    * Register user and give auth token
    *
    * @param Request $request
    * @return array $user
    * @author Gordan
    *******/
    public function register(Request $request) {
        DB::beginTransaction();
        $last       = User::all()->last();
        $code_email = md5($request->email);
        if($last == null) $id = 1; else
            $count = $last->id + 1;
            $id    = "id".$count;
            User::create([
                'name'          => $request->name,
                'email'         => $request->email,
                'auth_token'    => md5($request->email).Str::random(32),
                'password'      => bcrypt($request->password),
                'confirm_email' => $code_email,
                'nickname'      => $id,
            ]);
           $url = "http://taglife.ru/confirmemail?code=".$code_email;
           Mail::to($request->email)
               ->send(new ConfirmEmail($request->name, $url));
        DB::commit();
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $user_t = User::find(Auth::id());
        $user_t -> auth_token = md5($request->email).Str::random(32);
        $user_t -> save();
        $user   = User::where('email',$request->email)->first();
        return $user->toJson();
    }
    /********
    * Logout user and delete auth token from DB
    *
    * @param  string $auth_token
    * @param Request $request
    * @return string
    * @author Gordan
    *******/
    public function logout(Request $request) {
        $user = User::find($request->id);
        $user -> auth_token = Str::random(15);
        $user -> SnsToken = null;
        $user -> save();
        return "Logout Success";
    }
    /**
     * @param Request $request
     * @return string
     */
    public function confirmEmail(Request $request) {
        $user = User::where('confirm_email', $request->code)->first();
        if($user == null || $user->confirm_email == 1) return "Error";
        $user -> confirm_email = 1;
        $user -> save();
        return view('pages.confirmEmail');
    }
    /**
     * @param Request $request
     * @return string
     */
    public function forgotPassword(Request $request) {
        $user = User::where('email', $request->email)
            ->where('confirm_email', '1')
            ->first();
        if($user == null) return "False";
        $token  = str_random('20');
        $url    = "http://taglife.ru/resetpassword?token=".$token;
        $bd     =  DB::table('password_resets')
                ->insert(['email' => $request->email, 'token' => $token]);
        Mail::to($request->email)->send(new ResetPasswordEmail($url));
        return "Send Email";

    }
    /**
     * @param Request $request
     * @return string
     */
    public function resetPassword(Request $request) {
        if($request->isMethod('get')) {
            $token = DB::table('password_resets')
                ->where('token', $request->token)
                ->first();
            if($token  == null) return redirect('/');
            return view('pages.resetPassword')->with(['token' => $token]);
        }
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'password' => 'required|confirmed',
            ]);
            $mail = DB::table('password_resets')
                ->where('token', $request->token)
                ->first();
            $user   =  User::where('email', $mail->email)->first();
            $user   -> password = bcrypt($request->password);
            $user   -> save();
            $mail = DB::table('password_resets')
                ->where('token', $request->token)
                ->delete();
            return redirect('/');
        }
        return "Error";
    }

}
