<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

class Album extends Model
{
    protected $fillable = [
        'author', 'name', 'active',
    ];
    public function author()
    {
        return $this->belongsTo(User::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions', 'album', 'user');
    }
    public static function getValidator(array $data)
    {
        return Validator::make($data, [
            'author' => 'numeric|exists:users,id|required',
            'name' => 'max:255|required',
            'active' => 'numeric|between:0,1|required',
        ]);
    }
    public static function create(array $attributes = Array())
    {
        $validator = Album::getValidator($attributes);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        else return parent::create($attributes);
    }
    public function save(array $options = Array())
    {
        $validator = Album::getValidator([
            'author' => $this->author,
            'name' => $this->name,
            'active' => $this->active,
        ]);
        if($validator->fails()) {
            return ['validation_errors' => $validator->errors()->all()];
        }
        else return parent::save($options);
    }
}
