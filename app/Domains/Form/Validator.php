<?php declare(strict_types=1);

namespace App\Domains\Form;

use App\Domains\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    public static function configContact(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required',
                'email' => 'required|email',
                'message' => 'required',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'email.required' => __('validator.email-required'),
                'email.email' => __('validator.email-email'),
                'message.required' => __('validator.message-required'),
            ]
        ];
    }
}
