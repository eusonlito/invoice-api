<?php declare(strict_types=1);

namespace App\Services\Model\Product;

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
                'reference' => 'string',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'price.required' => __('validator.price-required'),
                'price.numeric' => __('validator.price-numeric'),
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
