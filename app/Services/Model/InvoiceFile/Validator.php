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
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,odt,ods,jpeg',
            ],

            'messages' => [
                'file.required' => __('validator.file-required'),
                'file.file' => __('validator.file-file'),
                'file.mimes' => __('validator.file-mimes', ['mimes' => 'pdf, doc, docx,xls, xlsx, odt, ods y jpg']),
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
