<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * App\ResizedPhoto
 *
 * @property integer $id
 * @property integer $photo
 * @property string $src
 * @property string $status
 * @property string $comment
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $size
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto wherePhoto($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereSrc($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereComment($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\ResizedPhoto whereSize($value)
 * @mixin \Eloquent
 */
class ResizedPhoto extends Model
{
    use SafeModelTrait;

    protected $appends = [
        'resized_full_path',
    ];

    protected $fillable = [
        'size', 'src', 'photo', 'status',
    ];
    
    public function original()
    {
        return $this->belongsTo(Photo::class, 'photo');
    }

    public function getResizedFullPathAttribute()
    {
        if($this->src === null || $this->src === '') {
            return null;
        }
        /**        
         * Can't use $this->original->album for some reason here
         * Looks like there's also an "original" property that returns initial object and overlaps the custom one
         * TODO: rename "original" method of "ResizedPhoto" model
         */       
        $photo = Photo::findOrFail($this->photo);
        return url("/resized/{$photo->album}/{$this->src}");
    }

    private static function prepareValidator(array $data, $id = 0, $required = true)
    {
        return Validator::make($data, [
            'status' => 'in:new,in_progress,complete,error',
            'size' => 'required|string|regex:/^\d{3,5}x\d{3,5}$/',
        ]);
    }
}
