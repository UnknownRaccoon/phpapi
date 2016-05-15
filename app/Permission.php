<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * App\Permission
 *
 * @property integer $id
 * @property integer $user
 * @property integer $album
 * @property string $access
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereAlbum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereAccess($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends Model
{
    use SafeModelTrait;

    protected $fillable = [
        'user', 'album', 'access'
    ];

    public static function set(array $data)
    {
        Permission::validate($data);
        if(Album::find($data['album'])->author === $data['user'] || User::find($data['user'])->isAdmin()) {
            return true;
        }
        $instance = Permission::whereUser($data['user'])->whereAlbum($data['album'])->first();
        if($instance === null) {
            if($data['access'] !== 'denied') {
                Permission::create($data);
            }
        }
        else {
            if($data['access'] === 'denied') {
                $instance->delete();
            }
            else {
                $instance->update($data);
            }
        }
        return true;
    }

    private static function prepareValidator(array $data = [], $id = 0, $required)
    {
        return Validator::make($data, [
            'user' => "required|exists:users,id,role,!admin}",
            'album' => 'required|numeric|exists:albums,id',
            'access' => 'required|in:full,read,denied',
        ]);
    }
}
