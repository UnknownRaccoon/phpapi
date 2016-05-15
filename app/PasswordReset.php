<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Validator;

/**
 * App\PasswordReset
 *
 * @mixin \Eloquent
 * @property integer $id
 * @property integer $user
 * @property string $token
 * @property boolean $used
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereUser($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereUsed($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PasswordReset whereUpdatedAt($value)
 */
class PasswordReset extends Model
{
    use SafeModelTrait;

    protected $fillable = ['user', 'token', 'used'];

    private static function prepareValidator(array $data, $id = 0, $required = true)
    {
        return Validator::make($data, [
            'user' => 'required|exists:users,id',
            'token' => 'required|string',
        ]);
    }
}
