<?php declare(strict_types=1);

namespace App\Domain\InvoiceFile;

use App\Domain\ValidatorAbstract;

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
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,odt,ods,jpeg,png',
            ],

            'messages' => [
                'file.required' => __('validator.file-required'),
                'file.file' => __('validator.file-file'),
                'file.mimes' => __('validator.file-mimes', ['mimes' => 'pdf, doc, docx,xls, xlsx, odt, ods, jpg y png']),
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
