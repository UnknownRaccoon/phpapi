<?php

namespace App\Http\Controllers;

use Auth, Gate, Hash;
use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => 'store']);
    }

    /**
     * @api {get} /users/ Get User List
     *
     * @apiGroup Users
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "role": "admin",
     *              "name": "first",
     *              "username": "first",
     *              "phone": "+380482407401",
     *              "created_at": "2016-04-25 13:38:26",
     *              "updated_at": "2016-04-25 13:38:26"
     *          }
     *      ]
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"You have no acces to this function",
     *          "code":403
     *     }
     */
    public function index()
    {
        if(!Auth::user()->isAdmin()) {
            return $this->jsonErrorResponse('You have no acces to this function', 403);
        }
        return response()->json(User::all());
    }

    /**
     * @api {post} /users/ Add New User
     *
     * @apiParam {String} username Username
     * @apiParam {String} name User's real name
     * @apiParam {String} role User's role
     * @apiParam {String} password User's password
     * @apiParam {String} email User's email
     * @apiGroup Users
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "username": "third",
     *         "name": "third",
     *         "role": "client",
     *         "updated_at": "2016-04-26 08:26:18",
     *         "created_at": "2016-04-26 08:26:18",
     *         "id": 3
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"This action is unauthorized.",
     *          "code":403
     *     }
     */
    public function store(Request $request)
    {
        User::validate($request->all());
        $request->merge(['password' => Hash::make($request->all()['password'])]);
        return response()->json(User::create($request->all()), 201);
    }

    /**
     * @api {get} /users/:user Get User
     *
     * @apiParam {Number} user User identifier
     * @apiGroup Users
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "role": "admin",
     *              "name": "first",
     *              "username": "first",
     *              "phone": "+380482407401",
     *              "created_at": "2016-04-25 13:38:26",
     *              "updated_at": "2016-04-25 13:38:26"
     *          }
     *      ]
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"This action is unauthorized.",
     *          "code":403
     *     }
     */
    public function show(User $user)
    {
        if(Gate::denies('access', $user)) {
            return $this->jsonErrorResponse('Access denied', 403);
        }
        return response()->json($user);
    }

    /**
     * @api {put} /users/:user Update User Data
     *
     * @apiParam {Number} user User identifier
     * @apiParam {String} username Username
     * @apiParam {String} name User's real name
     * @apiParam {String} role User's role
     * @apiParam {String} password User's password
     * @apiParam {String} email User's email
     * @apiGroup Users
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"This action is unauthorized.",
     *          "code":403
     *     }
     */
    public function update(Request $request, User $user)
    {
        if(Gate::denies('access', $user)) {
            return $this->jsonErrorResponse('Access denied', 403);
        }
        User::validate($request->all(), $user->id, false);
        if($request->exists('password')) {
            $request->merge(['password' => Hash::make($request->all()['password'])]);
        }
        if(!Auth::user()->isAdmin()) {
            $request->merge(['role' => Auth::user()->role]);
        }
        $user->update($request->all());
        return response()->json([], 204);
    }

    /**
     * @api {delete} /users/:id Delete User
     *
     * @apiParam {Number} id User identifier
     * @apiGroup Users
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"This action is unauthorized.",
     *          "code":403
     *     }
     */
    public function destroy(User $user)
    {
        if(Gate::denies('access', $user)) {
            return $this->jsonErrorResponse('Access denied', 403);
        }
        $user->delete();
        return response()->json([], 204);
    }
}
    