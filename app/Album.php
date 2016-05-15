<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * App\Album
 *
 * @property integer $id
 * @property integer $author
 * @property string $name
 * @property boolean $active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereAuthor($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Album whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Album extends Model
{
    use SafeModelTrait;

    protected $fillable = [
        'author', 'active', 'name',
    ];

    /**
     * Returns author of the album
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Returns album photos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function photos()
    {
        return $this->hasMany(Photo::class, 'album');
    }

    /**
     * Returns the list of users that have access to album
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'permissions', 'album', 'user');
    }

    /**
     * Validates model data, used inside SafeModelTrait
     *
     * @param array $data
     * @param int $id
     * @param $required
     * @return \Illuminate\Validation\Validator
     */
    private static function prepareValidator(array $data = [], $id = 0, $required = true)
    {
        $validationArray = [
            'name' => 'max:50',
            'active' => 'in:0,1',
            'author' => 'exists:users,id,role,!client',
        ];
        if($required) {
            $validationArray['name'] .= '|required';
            $validationArray['active'] .= '|required';
            $validationArray['author'] .= '|required';
        }
        return Validator::make($data, $validationArray);
    }
}
