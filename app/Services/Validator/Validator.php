<?php declare(strict_types=1);

namespace App\Services\Validator;

use Illuminate\Support\Facades\Validator as FacadeValidator;
use Illuminate\Validation\Validator as BaseValidator;
use App\Exceptions;

class Validator
{
    /**
     * @param array $input
     * @param array $rules
     * @param array $messages
     * @param bool $all
     *
     * @return array
     */
    public static function validate(array $input, array $rules, array $messages, bool $all): array
    {
        $validator = FacadeValidator::make($input, $rules, $messages);

        static::check($validator);

        return (new Data($validator->validated(), $rules, $all))->get();
    }

    /**
     * @param \Illuminate\Validation\Validator $validator
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return void
     */
    protected static function check(BaseValidator $validator)
    {
        if ($validator->fails() === false) {
            return;
        }

        $messages = call_user_func_array('array_merge', $validator->errors()->messages());

        throw new Exceptions\ValidatorException(implode("\n", $messages));
    }
}
