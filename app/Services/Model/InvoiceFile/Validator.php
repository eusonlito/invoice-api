<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceFile;

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
                'file' => 'required|file',
            ],

            'messages' => [
                'file.required' => __('validator.file-required'),
                'file.file' => __('validator.file-file'),
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
