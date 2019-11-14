<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use Illuminate\Support\Facades\Storage;
use App\Exceptions\ValidatorException;
use App\Models;

class StoreCss
{
    /**
     * @var string
     */
    protected static string $path = 'invoice-configuration/css';

    /**
     * @var array
     */
    protected static array $ids = ['logo', 'company', 'info', 'client', 'items', 'total', 'comment'];

    /**
     * @param \App\Models\Company $company
     * @param string $css
     *
     * @return string
     */
    public static function save(Models\Company $company, string $css): string
    {
        static::validate($css);

        $file = static::$path.'/'.$company->id.'.css';

        Storage::disk('private')->put($file, $css);

        return $file;
    }

    /**
     * @param string $css
     *
     * @return void
     */
    public static function validate(string $css)
    {
        foreach (static::$ids as $id) {
            if (strpos($css, '#'.$id) === false) {
                throw new ValidatorException(__('validator.css-format'));
            }
        }
    }
}
