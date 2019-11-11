<?php declare(strict_types=1);

namespace App\Services\Model\Payment;

use App\Services\Model\ValidatorAbstract;

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
                'enabled' => 'boolean',
                'description' => 'string',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
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
