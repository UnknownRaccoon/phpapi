<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;

/**
 * App\User
 *
 * @property integer $id
 * @property string $role
 * @property string $name
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRole($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUsername($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    use SafeModelTrait;

    //TODO: remove password hashing from controller and implement it using mutator
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'username', 'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Returns list of albums created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function albumsCreated()
    {
        return $this->hasMany(Album::class, 'author');
    }

    /**
     * Returns list of albums that user is allowed to view
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function albumsAllowed()
    {
        return $this->belongsToMany(Album::class, 'permissions', 'user', 'album');
    }

    /**
     * Returns list of all albums user has access to
     *
     * @return \Illuminate\Database\Eloquent\Collection|mixed|static[]
     */
    public function albumsAllowedIncludingOwn()
    {
        if($this->role === 'admin') {
            return Album::all();
        }
        if($this->role === 'photographer') {
            return $this->albumsCreated->merge($this->albumsAllowed);
        }
        if($this->role == 'client') {
            return $this->albumsAllowed;
        }
    }

    /**
     * Returns user's access level for specified album
     *
     * @param \App\Album $album
     * @return mixed|string
     */
    public function getAccess(Album $album)
    {
        if($this->isAdmin() || $album->author === $this->id) return 'full';
        $permission = Permission::whereUser($this->id)->whereAlbum($album->id)->first();
        if($permission === null) return 'denied';
        return $permission->access;
    }

    /**
     * Validates model data, used inside SafeModelTrait
     * 
     * @param array $data
     * @param int $id
     * @param bool $required
     * @return \Illuminate\Validation\Validator
     */
    private static function prepareValidator(array $data = [], $id = 0, $required = true)
    {
        $validationArray = [
            'role' => 'in:admin,photographer,client',
            'name' => 'alpha|max:50',
            'username' => 'max:30|unique:users,username',
            'email' => 'email|max:255|unique:users,email',
            'password' => 'between:4,60',
        ];
        if($id > 0) {
            $validationArray['username'] .= ",{$id}";
            $validationArray['email'] .= ",{$id}";
        }
        if($required) {
            $validationArray['username'] .= '|required';
            $validationArray['password'] .= '|required';
            $validationArray['role'] .= '|required';
            $validationArray['email'] .= '|required';
        }
        return Validator::make($data, $validationArray);
    }
}
