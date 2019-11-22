<?php declare(strict_types=1);

namespace App\Domains\ClientAddress;

use App\Domains\ValidatorAbstract;

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
                'name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'state' => 'required',
                'postal_code' => 'required',
                'country' => 'required',
                'phone' => 'string',
                'email' => 'email|string',
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
