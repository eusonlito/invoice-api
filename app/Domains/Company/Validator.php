<?php declare(strict_types=1);

namespace App\Domains\Company;

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
    public static function configUpdate(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required|string',
                'address' => 'required|string',
                'city' => 'required|string',
                'state' => 'required|string',
                'postal_code' => 'required|string',
                'tax_number' => 'required|string',
                'phone' => 'required',
                'email' => 'required|email',
                'country_id' => 'required|integer|exists:country,id',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'address.required' => __('validator.address-required'),
                'city.required' => __('validator.city-required'),
                'state.required' => __('validator.state-required'),
                'postal_code.required' => __('validator.postal_code-required'),
                'tax_number.required' => __('validator.tax_number-required'),
                'phone.required' => __('validator.phone-required'),
                'email.required' => __('validator.email-required'),
                'email.email' => __('validator.email-email'),
                'country_id.required' => __('validator.country_id-required'),
                'country_id.integer' => __('validator.country_id-required'),
                'country_id.exists' => __('validator.country_id-required'),
            ]
        ];
    }
}
