<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

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
                'name' => 'required',
                'number_prefix' => 'required',
                'number_fill' => 'integer',
                'number_next' => 'integer',
                'certificate_file' => 'file',
                'certificate_password' => 'string',
                'default' => 'boolean',
                'enabled' => 'boolean',
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'number_prefix.required' => __('validator.number_prefix-required'),
                'number_fill.integer' => __('validator.number_fill-integer'),
                'number_next.integer' => __('validator.number_next-integer'),
                'certificate_file.file' => __('validator.certificate_file-file'),
                'certificate_password.string' => __('validator.certificate_password-string'),
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
