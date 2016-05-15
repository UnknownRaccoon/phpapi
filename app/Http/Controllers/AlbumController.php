<?php

namespace App\Http\Controllers;

use Auth, Gate;
use Illuminate\Http\Request;
use App\Album;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * @api {get} /albums/ Get Albums List
     *
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *          {
     *              "id": 1,
     *              "author": 21,
     *              "name": "Kitties",
     *              "active": 1,
     *              "created_at": "2016-05-15 10:13:14",
     *              "updated_at": "2016-05-15 10:13:14"
     *          }
     *      ]
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 400 Bad Request
     *     {
     *         "error": "token_not_provided"
     *     }
     */
    public function index()
    {
        return response()->json(Auth::user()->albumsAllowedIncludingOwn());
    }

    /**
     * @api {post} /albums/ Add New Album
     * 
     * @apiParam {Number} author Author identifier
     * @apiParam {String} name Album name
     * @apiParam {Number} active Album state
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
        if(Gate::denies('create-album')) {
            return $this->jsonErrorResponse('You have no rights to create an album', 403);
        }
        if(!Auth::user()->isAdmin()) {
            $request->merge(['author' => Auth::user()->id]);
        }
        Album::validate($request->all());
        return response()->json(Album::create($request->all()), 201);
    }

    /**
     * @api {get} /albums/:album Get Album
     * 
     * @apiParam {Number} album Album identifier
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
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 404 Not Found
     *     {
     *         "error": "No query results for model [App\\Album].",
     *         "code": 404
     *     }
     */
    public function show(Album $album)
    {
        if(Gate::denies('access', $album)) {
            return $this->jsonErrorResponse('You have no rights to view this album', 403);
        }
        return response()->json($album);
    }

    /**
     * @api {put} /albums/:album Update Album Data
     * 
     * @apiParam {Number} album Album identifier
     * @apiParam {Number} author Author identifier
     * @apiParam {String} name Album name
     * @apiParam {Number} active Album state
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 406 Not Acceptable
     *     {
     *         "validation_errors": [
     *             "The name field is required."
     *         ]
     *     }
     */
    public function update(Request $request, Album $album)
    {
        if(Gate::denies('edit', $album)) {
            return $this->jsonErrorResponse('You have no rights to change this album', 403);
        }
        if(!Auth::user()->isAdmin()) {
            $request->merge(['author' => $album->author]);
        }
        $album->update($request->all());
        return response()->json([], 204);
    }

    /**
     * @api {delete} /albums/:album Delete Album
     * 
     * @apiParam {Number} album Album identifier
     * @apiGroup Albums
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *          "error":"You have no rights to delete this album",
     *          "code":403
     *     }
     */
    public function destroy(Album $album)
    {
        if(Gate::denies('edit', $album)) {
            return $this->jsonErrorResponse('You have no rights to delete this album', 403);
        }
        $album->delete();
        return response()->json([], 204);
    }
}
