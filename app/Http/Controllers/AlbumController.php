<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Album;
use JWTAuth;
use Tymon\JWTAuthExceptions\JWTException;
use Auth;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }
    public function index()
    {
        return response()->json(Auth::user()->getAllowedAlbums());
    }
    public function store(Request $request)
    {
        $object = Album::createNew($request);
        if(array_key_exists('auth_error', $object)) {
            return response()->json($object, 403);
        }
        if(array_key_exists('validation_errors', $object)) {
            return response()->json($object, 400);
        }
        return response()->json($object, 201);
    }
    public function update(Request $request, Album $albums)
    {
        $result = $albums->updateAlbum($request);
        if(array_key_exists('auth_error', $result)) {
            return response()->json($result, 403);
        }
        if(array_key_exists('validation_errors', $result)) {
            return response()->json($result, 400);
        }
        return response()->json($result, 204);
    }
    public function destroy(Request $request, Album $albums)
    {
        $result = $albums->deleteAlbum($request);
        if(array_key_exists('auth_error', $result)) {
            return response()->json($result, 403);
        }
        return response()->json($result, 204);
    }
    public function show(Request $request, Album $albums)
    {
        $object = $albums->showAlbum($request);
        if(array_key_exists('auth_error', $object)) {
            return response()->json($object, 403);
        }
        return response()->json($object);
    }
}
