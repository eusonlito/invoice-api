<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

use App\Exceptions\ValidatorException;
use App\Models\InvoiceSerie as Model;
use App\Services\Pdf\Pdf;

class StoreCss
{
    /**
     * @var string
     */
    protected static string $default = 'resources/views/pdf/pages/invoice/default.css';

    /**
     * @var string
     */
    protected static string $path = 'invoice-serie/css';

    /**
     * @var array
     */
    protected static array $ids = ['logo', 'company', 'info', 'client', 'items', 'total', 'comment'];

    /**
     * @param ?\App\Models\InvoiceSerie $row = null
     *
     * @return string
     */
    public static function get(?Model $row = null): string
    {
        if ($row && $row->css && ($disk = Model::disk()) && $disk->exists($row->css)) {
            return $disk->get($row->css);
        }

        return file_get_contents(base_path(static::$default));
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     * @param string $css
     *
     * @return string
     */
    public static function preview(Model $row, string $css): string
    {
        return Pdf::binary((string)view('pdf.pages.invoice.preview', [
            'serie' => $row,
            'css' => static::validate($css)
        ]));
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     * @param string $css
     *
     * @return string
     */
    public static function save(Model $row, string $css): string
    {
        $file = static::$path.'/'.$row->company_id.'-'.$row->id.'.css';

        $row::disk()->put($file, static::validate($css));

        return $file;
    }

    /**
     * @param string $css
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return string
     */
    public static function validate(string $css): string
    {
        foreach (static::$ids as $id) {
            if (strpos($css, '#'.$id) === false) {
                throw new ValidatorException(__('validator.css-format'));
            }
        }

        return $css;
    }
}
