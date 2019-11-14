<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use Illuminate\Support\Facades\Storage;
use App\Models;
use App\Models\InvoiceConfiguration as Model;

class Value
{
    /**
     * @param \App\Models\Company $company
     *
     * @return string
     */
    public static function cssByCompany(Models\Company $company): string
    {
        $disk = Storage::disk('private');
        $row = Model::byCompany($company)->where('key', 'css')->first();

        if ($row && $row->value && $disk->exists($row->value)) {
            return $disk->get($row->value);
        }

        return file_get_contents(base_path('resources/views/pdf/pages/invoice/default.css'));
    }
}
