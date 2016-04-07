<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Gate;
use Validator;
use Hash;
use Illuminate\Support\Facades\Input;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function albumsCreated()
    {
        return $this->hasMany(Album::class, 'author');
    }
    public function albumsAllowed()
    {
        return $this->belongsToMany(Album::class, 'permissions', 'user', 'album')->withPivot('access');
    }
    public function getAllowedAlbums()
    {
        if($this->role === 'admin') {
            return Album::all();
        }
        if($this->role === 'photographer') {
            return Album::join('permissions', 'albums.id', '=', 'album')
                                ->where('user', $this->id)
                                ->select('albums.id', 'author', 'name', 'active', 'albums.created_at', 'albums.updated_at')
                                ->union(Album::where('author', $this->id))->get();
        }
        if($this->role === 'client') {
            return $this->albumsAllowed;
        }
    }
    public static function getUsers()
    {
        if(Gate::denies('view-profiles')) {
            return ['auth_error' => 'Access denied'];
        }
        return User::all();
    }
    public static function createNew(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'name' => 'required|alpha|max:255',
            'password' => 'required|between:4,20',
            'role' => 'required|in:admin,photographer,client',
        ]);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        $user = new User;
        $user->role = $request->input('role');
        $user->name = $request->input('name');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        return $user;
    }
    public function updateUser(Request $request)
    {
        if(Gate::denies('change-profile', $this)) {
            return ['auth_error' => 'You have no permission to edit this profile'];
        }
        $validator = Validator::make($request->all(), [
            'username' => 'unique:users|max:255',
            'name' => 'alpha|max:255',
            'password' => 'between:4,20',
            'role' => 'in:admin,photographer,client',
        ]);
        if(Input::exists('username')) $this->username = $request->input('username');
        if(Input::exists('name')) $this->name = $request->input('name');
        if(Input::exists('password')) $this->password = Hash::make($request->input('password'));
        if(Input::exists('role') && $this->role === 'admin') $this->role = $request->input('role');
        $this->save();
        return [];
    }
    public function deleteUser(Request $request) {
        if(Gate::denies('change-profile', $this)) {
            return ['auth_error' => 'You have no permission to delete this profile'];
        }
        User::destroy($this->id);
        return [];
    }
    public function showAlbum(Request $request) {
        if(Gate::denies('change-profile', $this)) {
            return ['auth_error' => 'You have no permission to view this profile'];
        }
        return $this;
    }
}
