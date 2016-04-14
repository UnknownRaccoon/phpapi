<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Hash;
//use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'password', 'role',
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
            return Album::join('permissions', 'albums.id', '=', 'album')
                                ->where('user', $this->id)
                                ->select('albums.id', 'author', 'name', 'active', 'albums.created_at', 'albums.updated_at')->get();
        }
    }
    public static function getValidator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required|unique:users|max:255',
            'name' => 'required|alpha|max:255',
            'password' => 'required|between:4,60',
            'role' => 'required|in:admin,photographer,client',
        ]);
    }
    public static function create(array $attributes = Array())
    {
        $validator = User::getValidator($attributes);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        else {
            $attributes['password'] = Hash::make($attributes['password']);
            return parent::create($attributes);
        }
    }
    public function save(array $options = Array())
    {
        $validator = User::getValidator([
            'username' => $this->username,
            'name' => $this->name,
            'password' => $this->password,
            'role' => $this->role,
        ]);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        else return parent::save($options);
    }
}
