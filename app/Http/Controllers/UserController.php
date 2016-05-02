<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends RESTController
{
    public function __construct()
    {
        $this->authRequired = ['index', 'update', 'show', 'delete'];
        $this->model = 'App\User';
        $this->middleware('jwt.auth', ['except' => ['store']]);
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
     *          "error":"This action is unauthorized.",
     *          "code":403
     *     }
     */
    public function index()
    {
        return parent::index();
    }

    /**
     * @api {post} /users/ Add New User
     *
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
        return parent::store($request);
    }

    /**
     * @api {get} /users/:id Get User
     * @apiParam {Number} id User identifier
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
    public function show($id)
    {
        return parent::show($id);
    }

    /**
     * @api {put} /users/:id Update User Data
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
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }

    /**
     * @api {delete} /users/:id Delete User
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
    public function destroy($id)
    {
        return parent::destroy($id);
    }
}
