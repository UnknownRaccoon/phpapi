<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Illuminate\Support\Facades\Cache;

class AlbumController extends RESTController
{
    public function __construct()
    {
        $this->model = 'App\Album';
        $this->middleware('jwt.auth');
    }

    /**
     * @api {get} /albums/ Get Album List
     *
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *         {
     *             "id": 1,
     *             "author": 1,
     *             "name": "Kitties",
     *             "active": 1,
     *             "created_at": "2016-04-28 09:09:03",
     *             "updated_at": "2016-04-28 09:09:03"
     *         }
     *     ]
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "token_not_provided"
     *     }
     */
    public function index()
    {
        return response()->json(Auth::user()->albumsAllowed());
    }

    /**
     * @api {post} /albums/ Add New Album
     *
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *     {
     *         "name": "Kitties",
     *         "author": "1",
     *         "active": "1",
     *         "updated_at": "2016-04-28 09:09:03",
     *         "created_at": "2016-04-28 09:09:03",
     *         "id": 1
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 406 Not Acceptable
     *     {
     *         "validation_errors": [
     *             "The author field is required.",
     *             "The name field is required."
     *         ]
     *     }
     */
    public function store(Request $request)
    {
        return parent::store($request);
    }

    /**
     * @api {get} /albums/:id Get Album
     * @apiParam {Number} id Album identifier
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *         "id": 1,
     *         "author": 1,
     *         "name": "Kitties",
     *         "active": 1,
     *         "created_at": "2016-04-28 09:09:03",
     *         "updated_at": "2016-04-28 09:09:03"
     *     }
     *
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "error": "No query results for model [App\\Album].",
     *         "code": 404
     *     }
     */
    public function show($id)
    {
        return parent::show($id);
    }

    /**
     * @api {put} /albums/:id Update Album Data
     * @apiParam {Number} id Album identifier
     * @apiGroup Albums
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
     * @api {delete} /albums/:id Delete Album
     * @apiParam {Number} id Album identifier
     * @apiGroup Albums
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
