<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Validator;
use Gate;
use Illuminate\Support\Facades\Input;
use App\User;
use Illuminate\Http\Request;

class Album extends Model
{
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions', 'album', 'user');
    }
    public static function createNew(Request $request)
    {
        if(Gate::denies('create-album')) {
            return ['auth_error' => 'You have no permission to create an album'];
        }
        $validator = Validator::make($request->all(), [
            'author' => 'required|numeric|exists:users,id',
            'name' => 'required|max:255',
            'active' => 'required|numeric|between:0,1',
        ]);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        $album = new Album;
        if(Auth::user()->role === 'admin') {
            $album->author = $request->input('author');
        } else if(Auth::user()->role === 'photographer') {
            $album->author = Auth::user()->id; 
        }
        $album->name = $request->input('name');
        $album->active = $request->input('active');
        $album->save();
        return $album;
    }
    public function updateAlbum(Request $request)
    {
        if(Gate::denies('change-album', $this)) {
            return ['auth_error' => 'You have no permission to change this album'];
        }
        $validator = Validator::make($request->all(), [
            'author' => 'numeric|exists:users,id',
            'name' => 'max:255',
            'active' => 'numeric|between:0,1',
        ]);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        if(Auth::user()->role === 'admin') {
            if(Input::exists('author')) $this->author = $request->input('author');
        } else if(Auth::user()->role === 'photographer') {
            $this->author = Auth::user()->id; 
        }
        if(Input::exists('name')) $this->name = $request->input('name');
        if(Input::exists('active')) $this->active = $request->input('active');
        $this->save();
        return [];
    }
    public function deleteAlbum(Request $request) {
        if(Gate::denies('change-album', $this)) {
            return ['auth_error' => 'You have no permission to delete this album'];
        }
        Album::destroy($this->id);
        return [];
    }
    public function showAlbum(Request $request) {
        if(Gate::denies('view-album', $this)) {
            return ['auth_error' => 'You have no permission to view this profile'];
        }
        return $this;
    }
}
