<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\PasswordReset;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Mail, Hash;

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
    }

    /**
     * @api {post} /reset/ Generate Token
     *
     * @apiGroup Password Reset
     * @apiParam {String} email User's email
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 406 Not Acceptable
     *     {
     *         "error": "No email provided"
     *     }
     */
    public function reset(Request $request)
    {
        if(!$request->exists('email')) {
            return $this->jsonErrorResponse('No email provided', 406);
        }
        $user = User::whereEmail($request->all()['email'])->firstOrFail();
        $token = PasswordReset::create([
            'user' => $user->id,
            'token' => str_random(30),
            'used' => false
        ]);
        $message = 'Your password reset token: ' . $token->token;
        Mail::raw($message, function($m) use ($user)
        {
            $m->from('intersog.labs@gmail.com');
            $m->to($user->email)->subject('Password reset token');
        });
        return response()->json([], 204);
    }

    /**
     * @api {post} /reset/:token Reset Password
     *
     * @apiGroup Password Reset
     * @apiParam {String} token Password reset token
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 406 Not Acceptable
     *     {
     *         "error": "Token has already been used"
     *     }
     */
    public function setNew(Request $request, $token)
    {
        $token = PasswordReset::whereToken($token)->firstOrFail();
        if($token->used) {
            return $this->jsonErrorResponse('Token has already been used', 406);
        }
        if($token->created_at <= Carbon::now()->subMinutes(env('TOKEN_LIFETIME', 60))) {
            return $this->jsonErrorResponse('Token expired', 406);
        }
        $user = User::find($token->user);
        $this->validate($request, ['password' => 'required|min:4']);
        $user->password = Hash::make($request->input('password'));
        $user->save();
        $token->used = true;
        $token->save();
        return response()->json([], 204);
    }
}
