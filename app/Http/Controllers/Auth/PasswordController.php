<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use App\Token;
use Auth;
use Mail;
use Hash;
use Carbon\Carbon;
class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
        $this->middleware('jwt.auth');
    }
    public function reset()
    {
        $user = Auth::user();
        $token = Token::create(['user' => Auth::id(), 'token' => str_random(30), 'used' => false]);
        $message = 'Your password reset token: ' . $token->token;
        Mail::raw($message, function($m) use ($token, $user)
        {
            $m->from('intersog.labs@gmail.com');
            $m->to($user->email)->subject('Password reset token');
        });
        return response()->json([], 204);
    }
    public function setNew(Request $request, $token)
    {
        $token = Token::where('token', $token)->firstOrFail();
        if(!$token->used) {
            if($token->created_at >= Carbon::now()->subMinutes(60)) {
                $this->validate($request, ['password' => 'required|min:4']);
                Auth::user()->password = Hash::make($request->input('password'));
                Auth::user()->save();
                $token->used = true;
                $token->save();
                return response()->json([], 204);
            }
        }
    }
}
