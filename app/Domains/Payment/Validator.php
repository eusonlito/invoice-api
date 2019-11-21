<?php declare(strict_types=1);

namespace App\Domains\Payment;

use App\Domains\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    public static function configUpdate(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required|string',
                'description' => 'string',
                'enabled' => 'boolean',
                'default' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'default.boolean' => __('validator.default-boolean'),
                'enabled.boolean' => __('validator.enabled-boolean'),
            ]
        ];
    }

    /**
     * @return array
     */
    public static function configCreate(): array
    {
        return static::configUpdate();
    }
}
