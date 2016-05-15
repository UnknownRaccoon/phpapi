<?php

namespace App\Http\Controllers;

use Gate;
use Illuminate\Http\Request;
use App\Album;
use App\Permission;
use App\User;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * @api {get} /albums/:album/permissions Get Permission List
     *
     * @apiParam {Number} album Album identifier
     * @apiGroup Permissions
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *         {
     *             "id": 23,
     *             "role": "client",
     *             "username": "fourth",
     *             "name": "meow",
     *             "email": "mrm1r1mrte1st@gmail.com",
     *             "created_at": "2016-05-15 10:09:55",
     *             "updated_at": "2016-05-15 10:09:55",
     *             "access": "full"
     *         }
     *     ]
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You have no rights to use this function"
     *     }
     */
    public function index(Album $album)
    {
        if(Gate::denies('access', $album)) {
            return $this->jsonErrorResponse('You have no rights to use this function', 403);
        }
        $users = User::join('permissions', 'users.id', '=', 'permissions.user')->select('users.*', 'permissions.access')->get();
        return response()->json($users);
    }

    /**
     * @api {post} /albums/:album/permissions Set Permission
     *
     * @apiParam {Number} album Album identifier
     * @apiParam {Number} user User identifier
     * @apiParam {String} access Access level
     * @apiGroup Permissions
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You have no rights to use this function"
     *     }
     */
    public function store(Request $request, Album $album)
    {
        if(Gate::denies('edit', $album)) {
            return $this->jsonErrorResponse('You have no rights to use this function', 403);
        }
        $request->merge(['album' => $album->id]);
        Permission::set($request->all());
        return response()->json([], 204);
    }
}
