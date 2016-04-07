<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Gate;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'store']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('username', 'password');
        try {
            if(!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch(JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }

    public function index()
    {
        $users = User::getUsers();
        if(array_key_exists('auth_error', $users)) {
            return response()->json($users, 403);
        }
        return response()->json($users);
    }
    public function store(Request $request)
    {
        $object = User::createNew($request);
        if(array_key_exists('validation_errors', $object)) {
            return response()->json($object, 400);
        }
        return response()->json($object, 201);
    }
    public function update(Request $request, User $users)
    {
        $result = $users->updateUser($request);
        if(array_key_exists('auth_error', $result)) {
            return response()->json($result, 403);
        }
        if(array_key_exists('validation_errors', $result)) {
            return response()->json($result, 400);
        }
        return response()->json($result, 204);
    }
    public function destroy(Request $request, User $users)
    {
        $result = $users->deleteUser($request);
        if(array_key_exists('auth_error', $result)) {
            return response()->json($result, 403);
        }
        return response()->json($result, 204);
    }
    public function show(Request $request, User $users)
    {
        $object = $users->showAlbum($request);
        if(array_key_exists('auth_error', $object)) {
            return response()->json($object, 403);
        }
        return response()->json($object);
    }
}
