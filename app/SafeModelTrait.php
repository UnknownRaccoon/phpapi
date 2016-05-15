<?php

namespace App;

use Validator;
use Illuminate\Validation\ValidationException;

/**
 * Ensures model is saved in valid state
 *
 * @package App
 */
trait SafeModelTrait
{
    /**
     * Override this method in your model to enable validation
     *
     * @param array $data
     * @param int $id
     * @param bool $required
     * @return \Illuminate\Validation\Validator
     */
    private static function prepareValidator(array $data, $id = 0, $required = true)
    {
        return Validator::make($data, []);
    }

    /**
     * Designed to validate model data.
     * Has to be static to make validating possible without having model's object
     *
     * @param array $data
     * @param int $id
     * @param bool $required
     * @return bool
     * @throws ValidationException
     */
    public static function validate(array $data, $id = 0, $required = true)
    {
        $validator = static::prepareValidator($data, $id, $required);
        if($validator->fails()) {
            throw new ValidationException($validator);
        }
        return true;
    }

    /**
     * Overrides default method to ensure model is valid every time it's saved
     *
     * @param array $attributes
     * @return mixed
     * @throws ValidationException
     */
    public function save(array $attributes = [])
    {
        $hidden = $this->hidden;
        static::validate($this->makeVisible($this->hidden)->toArray(), $this->id);
        $this->setHidden($hidden);
        return parent::save($attributes);
    }
}
