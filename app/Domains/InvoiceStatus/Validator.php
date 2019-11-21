<?php declare(strict_types=1);

namespace App\Domains\InvoiceStatus;

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
                'order' => 'required|integer',
                'paid' => 'boolean',
                'default' => 'boolean',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'order.required' => __('validator.order-required'),
                'order.integer' => __('validator.order-integer'),
                'paid.boolean' => __('validator.paid-boolean'),
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
