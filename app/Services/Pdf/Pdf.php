<?php declare(strict_types=1);

namespace App\Services\Pdf;

use Illuminate\Support\Facades\Storage;
use mikehaertl\wkhtmlto\Pdf as WPdf;
use App\Exceptions;
use App\Services;

class Pdf
{
    /**
     * @param string $html
     *
     * @throws \App\Exceptions\UnexpectedValueException
     *
     * @return \mikehaertl\wkhtmlto\Pdf
     */
    public static function load(string $html): WPdf
    {
        return (new WPdf(static::options()))->addPage(Services\Html\Html::fix($html), [], 'html');
    }

    /**
     * @return array
     */
    protected static function options(): array
    {
        $disk = Storage::disk('private');
        $disk->makeDirectory('tmp');

        $options = [
            'binary' => base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64'),
            'ignoreWarnings' => true,
            'tmpDir' => $disk->path('tmp'),
            'encoding' => 'UTF-8',
            'dpi' => 96,
            'no-outline'
        ];

        if (config('services.phpwkhtmltopdf.xserver')) {
            $options[] = 'use-xserver';
            $options['commandOptions'] = ['procEnv' => ['DISPLAY' => ':0']];
        }

        return $options;
    }

    /**
     * @param string $html
     *
     * @return string
     */
    public static function binary(string $html): string
    {
        return static::load($html)->toString();
    }

    /**
     * @param string $html
     * @param string $file
     *
     * @throws \App\Exceptions\UnexpectedValueException
     *
     * @return string
     */
    public static function save(string $html, string $file): string
    {
        $disk = Storage::disk('private');
        $disk->makeDirectory(dirname($file));

        $pdf = static::load($html);

        if (!$pdf->saveAs($disk->path($file))) {
            throw new Exceptions\UnexpectedValueException($pdf->getError());
        }

        return $file;
    }
}
