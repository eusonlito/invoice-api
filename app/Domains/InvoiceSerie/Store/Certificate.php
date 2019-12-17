<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use Illuminate\Http\UploadedFile;
use App\Exceptions\ValidatorException;
use App\Models\InvoiceSerie as Model;
use App\Services\Sign\SignFactory;

class Certificate extends StoreAbstract
{
    /**
     * @var string
     */
    protected string $path = 'invoice-serie/certificate_file';

    /**
     * @var array
     */
    protected array $mimes = [
        'application/pkix-cert', 'application/x-x509-ca-cert',
        'application/x-x509-user-cert', 'application/x-pkcs12',
    ];

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        if ($this->data['certificate_file']) {
            $this->row->certificate_file = $this->store($this->data['certificate_file']);
        } elseif ($this->row->certificate_file === null) {
            $this->row->certificate_file = '';
        }

        if ($this->data['certificate_password']) {
            $this->row->certificate_password = encrypt($this->data['certificate_password']);
        } elseif ($this->row->certificate_password === null) {
            $this->row->certificate_password = '';
        }

        if ($this->row->certificate_file) {
            $this->validate($this->row->certificate_file, $this->row->certificate_password);
        }

        return $this->row;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     *
     * @return string
     */
    protected function store(UploadedFile $file): string
    {
        if (in_array($file->getClientMimeType(), $this->mimes, true) === false) {
            throw new ValidatorException(__('validator.certificate_file-mimes'));
        }

        $name = explode('.', $file->getClientOriginalName());
        $ext = array_pop($name);
        $name = $this->row->id.'-'.str_slug(implode('-', $name), '-').'.'.strtolower($ext);

        $this->row::disk()->putFileAs($this->path, $file, $name);

        return $this->path.'/'.$name;
    }

    /**
     * @param string $file
     * @param string $password
     *
     * @return void
     */
    protected function validate(string $file, string $password): void
    {
        $certificate = $this->row::disk()->path($file);
        $password = $password ? decrypt($password) : '';

        SignFactory::get()->verify($certificate, $password);
    }
}
