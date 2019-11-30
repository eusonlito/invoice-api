<?php declare(strict_types=1);

namespace App\Domains\InvoiceRecurring;

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
                'every' => 'required|in:week,month,year',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'every.required' => __('validator.every-required'),
                'every.in' => __('validator.every-in'),
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
