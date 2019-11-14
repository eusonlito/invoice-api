<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use App\Services\Model\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    public static function configCss(): array
    {
        return [
            'all' => false,

            'rules' => [
                'css' => 'required|string',
            ],

            'messages' => [
                'css.required' => __('validator.css-required'),
                'css.string' => __('validator.css-string'),
            ]
        ];
    }
}
