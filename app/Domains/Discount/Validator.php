<?php declare(strict_types=1);

namespace App\Domains\Discount;

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
                'type' => 'required|in:fixed,percent',
                'value' => 'numeric',
                'description' => 'string',
                'default' => 'boolean',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'type.required' => __('validator.type-required'),
                'type.in' => __('validator.type-in'),
                'value.numeric' => __('validator.value-numeric'),
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
