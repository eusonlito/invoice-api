<?php declare(strict_types=1);

namespace App\Domains\InvoiceFile\Store;

use Illuminate\Http\UploadedFile;
use App\Models\InvoiceFile as Model;

class Upload extends StoreAbstract
{
    /**
     * @var string
     */
    protected string $path = 'invoice-file/file';

    /**
     * @return \App\Models\InvoiceFile
     */
    public function upload(): Model
    {
        $upload = $this->data['file'];

        $this->row->name = $upload->getClientOriginalName();
        $this->row->file = $this->store($upload);
        $this->row->main = false;

        $this->row->save();

        return $this->row;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $upload
     *
     * @return string
     */
    protected function store(UploadedFile $upload): string
    {
        $name = explode('.', $upload->getClientOriginalName());
        $ext = array_pop($name);

        $name = str_slug(microtime().'-'.implode('-', $name), '-');
        $name = $this->row->invoice->id.'-'.$name.'.'.strtolower($ext);

        $this->row::disk()->putFileAs($this->path, $upload, $name);

        return $this->path.'/'.$name;
    }
}
