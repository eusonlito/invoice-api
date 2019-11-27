<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie;

use Illuminate\Http\UploadedFile;
use App\Exceptions\ValidatorException;
use App\Models\InvoiceSerie as Model;
use App\Services\Sign\SignFactory;

class StoreCertificate
{
    /**
     * @var string
     */
    protected static string $path = 'invoice-serie/certificate_file';

    /**
     * @var array
     */
    protected static array $mimes = [
        'application/pkix-cert', 'application/x-x509-ca-cert',
        'application/x-x509-user-cert', 'application/x-pkcs12',
    ];

    /**
     * @param \App\Models\InvoiceSerie $row
     * @param \Illuminate\Http\UploadedFile|string|null $file
     * @param string $password
     *
     * @return \App\Models\InvoiceSerie
     */
    public static function save(Model $row, $file, string $password): Model
    {
        if ($file) {
            $row->certificate_file = static::store($row, $file);
        } elseif ($row->certificate_file === null) {
            $row->certificate_file = '';
        }

        if ($password) {
            $row->certificate_password = encrypt($password);
        } elseif ($row->certificate_password === null) {
            $row->certificate_password = '';
        }

        if ($row->certificate_file) {
            static::validate($row);
        }

        return $row;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string
     */
    protected static function store(Model $row, UploadedFile $file): string
    {
        if (in_array($file->getClientMimeType(), static::$mimes, true) === false) {
            throw new ValidatorException(__('validator.certificate_file-mimes'));
        }

        $name = explode('.', $file->getClientOriginalName());

        $ext = array_pop($name);

        $name = $row->id.'-'.str_slug(implode('-', $name), '-').'.'.strtolower($ext);

        $row::disk()->putFileAs(static::$path, $file, $name);

        return static::$path.'/'.$name;
    }

    /**
     * @param \App\Models\InvoiceSerie $row
     *
     * @return void
     */
    protected static function validate(Model $row): void
    {
        $certificate = $row::disk()->path($row->certificate_file);
        $password = $row->certificate_password ? decrypt($row->certificate_password) : '';

        SignFactory::get()->verify($certificate, $password);
    }
}
