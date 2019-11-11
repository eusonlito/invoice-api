<?php declare(strict_types=1);

namespace App\Services\Model\Company;

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
                'name' => 'required',
                'address' => 'required',
                'city' => 'required',
                'postal_code' => 'required',
                'tax_number' => 'required',
                'phone' => 'required',
                'email' => 'required|email',
                'state_id' => 'required|integer|exists:state,id',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'address.required' => __('validator.address-required'),
                'city.required' => __('validator.city-required'),
                'postal_code.required' => __('validator.postal_code-required'),
                'tax_number.required' => __('validator.tax_number-required'),
                'phone.required' => __('validator.phone-required'),
                'email.required' => __('validator.email-required'),
                'email.email' => __('validator.email-required'),
                'state_id.required' => __('validator.state_id-required'),
                'state_id.integer' => __('validator.state_id-required'),
                'state_id.exists' => __('validator.state_id-required'),
            ]
        ];
    }
}
