<?php declare(strict_types=1);

namespace App\Domains\Product;

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
                'reference' => 'string',
                'name' => 'required|string',
                'price' => 'required|numeric',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'reference.string' => __('validator.reference-string'),
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
