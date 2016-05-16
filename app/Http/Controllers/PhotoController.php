<?php

namespace App\Http\Controllers;

use App\ResizedPhoto;
use Gate, Cache, File;
use App\Album;
use App\Photo;
use Illuminate\Http\Request;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    /**
     * @api {get} /albums/:album/photos View Photos
     *
     * @apiParam {Number} album Album identifier
     * @apiGroup Photos
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     [
     *         {
     *             "id": 1,
     *             "album": 1,
     *             "image": "c2db1f45e648c4084b51281f3b067803.png",
     *             "created_at": "2016-05-15 10:18:28",
     *             "updated_at": "2016-05-15 10:18:28",
     *             "full_path": "http://localhost:85/img/1/c2db1f45e648c4084b51281f3b067803.png",
     *             "resized_existing": [
     *                 {
     *                     "id": 1,
     *                     "photo": 1,
     *                     "size": "100x100",
     *                     "src": "c2db1f45e648c4084b51281f3b067803100x100.png",
     *                     "status": "new",
     *                     "comment": null,
     *                     "created_at": "2016-05-15 10:18:28",
     *                     "updated_at": "2016-05-15 11:33:32",
     *                     "resized_full_path": "http://localhost:85/resized/1/c2db1f45e648c4084b51281f3b067803100x100.png"
     *                 }
     *             ]
     *         }
     *     ]
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You cannot view this album"
     *     }
     */
    public function index(Album $album)
    {
        if(Gate::denies('access', $album)) {
            return  $this->jsonErrorResponse('You cannot view this album', 403);
        }
        if(Cache::has("album_{$album->id}")) {
            return response(Cache::get("album_{$album->id}"), 200, ['Content-Type' => 'application/json']);
        }
        else {
            $data = $album->photos()->get()->toJson();
            Cache::put("album_{$album->id}", $data, 60);
            return response($data, 200, ['Content-Type' => 'application/json']);
        }
    }

    /**
     * @api {post} /albums/:album/photos Add photo
     *
     * @apiParam {Number} album Album identifier
     * @apiParam {Image} Image
     * @apiGroup Photos
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 201 Created
     *      {
     *          "album": 1,
     *          "image": "c1faa48108342295c9ee7263ec1ab38d.png",
     *          "updated_at": "2016-05-15 19:26:04",
     *          "created_at": "2016-05-15 19:26:04",
     *          "id": 3,
     *          "full_path": "http://localhost:85/img/1/c1faa48108342295c9ee7263ec1ab38d.png",
     *          "resized_existing": []
     *      }
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You have no rights to add photos to this album"
     *     }
     */
    public function store(Request $request, Album $album)
    {
        if(Gate::denies('edit', $album)) {
            return $this->jsonErrorResponse('You have no rights to add photos to this album', 403);
        }
        $request->merge(['album' => $album->id]);
        Photo::validate($request->all(), 0, false);
        $photo = Photo::create($request->all());
        Cache::forget("album_{$album->id}");
        ResizedPhoto::create([
            'src' => '',
            'size' => '100x100',
            'photo' => $photo->id,
        ]);
        ResizedPhoto::create([
            'src' => '',
            'size' => '400x400',
            'photo' => $photo->id,
        ]);
        return response()->json($photo, 201);
    }

    /**
     * @api {get} /albums/:album/photos/:photo View Photo
     *
     * @apiParam {Number} album Album identifier
     * @apiParam {Number} photo Photo identifier
     * @apiGroup Photos
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 200 OK
     *     {
     *          "id": 1,
     *          "album": 1,
     *          "image": "c2db1f45e648c4084b51281f3b067803.png",
     *          "created_at": "2016-05-15 10:18:28",
     *          "updated_at": "2016-05-15 10:18:28",
     *          "full_path": "http://localhost:85/img/1/c2db1f45e648c4084b51281f3b067803.png",
     *          "resized_existing": [
     *              {
     *                  "id": 1,
     *                  "photo": 1,
     *                  "size": "100x100",
     *                  "src": "c2db1f45e648c4084b51281f3b067803100x100.png",
     *                  "status": "new",
     *                  "comment": null,
     *                  "created_at": "2016-05-15 10:18:28",
     *                  "updated_at": "2016-05-15 11:33:32",
     *                  "resized_full_path": "http://localhost:85/resized/1/c2db1f45e648c4084b51281f3b067803100x100.png"
     *              }
     *          ]
     *     }
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You have no rights to view this photo"
     *     }
     */
    public function show(Album $album, Photo $photo)
    {
        if(Gate::denies('access', $album)) {
            return $this->jsonErrorResponse('You have no rights to view this photo', 403);
        }
        return response()->json($photo);
    }

    /**
     * @api {delete} /albums/:album/photos/:photo Delete Photo
     *
     * @apiParam {Number} album Album identifier
     * @apiParam {Number} photo Photo identifier
     * @apiGroup Photos
     * @apiSuccessExample Success-Response:
     *     HTTP/1.1 204 No Content
     * @apiErrorExample Error-Response:
     *     HTTP/1.1 403 Forbidden
     *     {
     *         "error": "You have no rights to delete this photo"
     *     }
     */
    public function destroy(Album $album, Photo $photo)
    {
        if(Gate::denies('edit', $album)) {
            return $this->jsonErrorResponse('You have no rights to delete this photo', 403);
        }
        File::delete(base_path() . "/public/img/{$photo->album}/{$photo->image}");
        $photo->delete();
        Cache::forget("album_{$album->id}");
        return response()->json([], 204);
    }
}
