<?php declare(strict_types=1);

namespace App\Services\Model\Client;

use App\Services\Model\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    protected static function configCreate(): array
    {
        return static::configUpdate();
    }

    /**
     * @return array
     */
    protected static function configUpdate(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required|string',
                'phone' => 'string',
                'email' => 'email|string',
                'contact_name' => 'string',
                'contact_surname' => 'string',
                'web' => 'string',
                'tax_number' => 'required|string',
                'contact_phone' => 'string',
                'contact_email' => 'email|string',
                'comment' => 'string',
                'discount_id' => 'nullable|integer',
                'payment_id' => 'nullable|integer',
                'shipping_id' => 'nullable|integer',
                'tax_id' => 'nullable|integer',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'email.email' => __('validator.email-email'),
                'tax_number.required' => __('validator.tax_number-required'),
                'contact_email.email' => __('validator.contact_email-email'),
                'discount_id.integer' => __('validator.discount_id-integer'),
                'payment_id.integer' => __('validator.payment_id-integer'),
                'shipping_id.integer' => __('validator.shipping_id-integer'),
                'tax_id.integer' => __('validator.tax_id-integer'),
            ]
        ];
    }
}
