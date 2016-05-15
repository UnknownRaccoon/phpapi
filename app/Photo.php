<?php

namespace App;

use Validator;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Photo
 *
 * @property integer $id
 * @property integer $album
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Photo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Photo whereAlbum($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Photo whereImage($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Photo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Photo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Photo extends Model
{
    use SafeModelTrait;

    protected $fillable = [
        'image', 'album',
    ];

    /**
     * Will automatically be added to responses, also accessible as properties
     *
     * @var array
     */
    protected $appends = [
        'full_path', 'resized_existing',
    ];

    /**
     * Returns all related resized photos
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resized()
    {
        return $this->hasMany(ResizedPhoto::class, 'photo');
    }

    public function getResizedExistingAttribute()
    {
        return $this->resized()->whereNotIn('src', [''])->get();
    }

    /**
     * Returns album that contains the photo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function albumCreatedIn()
    {
        return $this->belongsTo(Album::class, 'album');
    }

    /**
     * Accessor for "virtual" property
     *
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function getFullPathAttribute()
    {
        return url("/img/{$this->album}/{$this->image}");
    }

    /**
     * More or less safe photo saving
     *
     * @param array $data
     * @return static
     */
    public static function create(array $data = [])
    {
        $filename = hash_file('md5', $data['image']) . ".{$data['image']->guessExtension()}";
        $file = $data['image'];
        $data['image'] = $filename;
        $result = parent::create($data);
        $file->move("img/{$data['album']}", $filename);
        return $result;
    }

    /**
     * Validation, used in SafeModelTrait
     * Unused derived boolean parameter here is utilized to check whether we want to validate image or path
     *
     * @param array $data
     * @param int $id
     * @param bool $saving
     * @return \Illuminate\Validation\Validator
     */
    private static function prepareValidator(array $data, $id = 0, $saving = true)
    {
        $imageValidation = 'required';
        if(!$saving) $imageValidation .= '|image';
        else $imageValidation .= '|string';
        return Validator::make($data, [
            'album' => 'required|exists:albums,id',
            'image' => $imageValidation,
        ]);
    }
}
