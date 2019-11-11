<?php declare(strict_types=1);

namespace App\Services\Model\Discount;

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
                'type' => 'required|in:fixed,percent',
                'value' => 'numeric',
                'enabled' => 'boolean',
                'description' => 'string',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'type.required' => __('validator.type-required'),
                'type.in' => __('validator.type-in'),
                'value.numeric' => __('validator.value-numeric'),
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
