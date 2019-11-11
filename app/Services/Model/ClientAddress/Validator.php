<?php declare(strict_types=1);

namespace App\Services\Model\ClientAddress;

use App\Services\Model\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @param string $name
     *
     * @return array
     */
    protected static function config(string $name): array
    {
        return [
            'clientCreate' => static::configClientCreate(),
            'clientUpdate' => static::configClientUpdate()
        ][$name];
    }

    /**
     * @return array
     */
    protected static function configClientCreate(): array
    {
        return static::configClientUpdate();
    }

    /**
     * @return array
     */
    protected static function configClientUpdate(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'postal_code' => 'required',
                'country' => 'required',
                'phone' => 'string',
                'email' => 'email',
                'comment' => 'string',
                'tax_number' => 'required_if:billing,true|string',
                'billing' => 'boolean',
                'shipping' => 'boolean',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'address.required' => __('validator.address-required'),
                'city.required' => __('validator.city-required'),
                'state.required' => __('validator.state-required'),
                'postal_code.required' => __('validator.postal_code-required'),
                'country.required' => __('validator.country-required'),
                'email.email' => __('validator.email-email'),
                'tax_number.required_if' => __('validator.tax_number-required_if'),
                'billing.boolean' => __('validator.billing-boolean'),
                'shipping.boolean' => __('validator.shipping-boolean'),
                'enabled.boolean' => __('validator.enabled-boolean'),
            ]
        ];
    }
}
