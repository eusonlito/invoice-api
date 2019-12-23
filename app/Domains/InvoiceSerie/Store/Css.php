<?php declare(strict_types=1);

namespace App\Domains\InvoiceSerie\Store;

use App\Exceptions\ValidatorException;
use App\Models\InvoiceSerie as Model;
use App\Services\Pdf\Pdf;

class Css extends StoreAbstract
{
    /**
     * @var string
     */
    protected string $default = 'resources/views/pdf/pages/invoice/default.css';

    /**
     * @var string
     */
    protected string $path = 'invoice-serie/css';

    /**
     * @var array
     */
    protected array $ids = ['logo', 'company', 'info', 'client', 'items', 'total', 'comment'];

    /**
     * @return string
     */
    public function get(): string
    {
        if ($this->row && $this->row->css && ($disk = Model::disk()) && $disk->exists($this->row->css)) {
            return $disk->get($this->row->css);
        }

        return file_get_contents(base_path($this->default));
    }

    /**
     * @return string
     */
    public function preview(): string
    {
        return Pdf::binary((string)view('pdf.pages.invoice.preview', [
            'serie' => $this->row,
            'css' => $this->validate($this->data['css'])
        ]));
    }

    /**
     * @return \App\Models\InvoiceSerie
     */
    public function update(): Model
    {
        $this->row->css = $this->path.'/'.$this->row->company_id.'-'.$this->row->id.'.css';
        $this->row::disk()->put($this->row->css, $this->validate($this->data['css']));
        $this->row->save();

        $this->cacheFlush();

        service()->log('invoice_serie', 'update-css', $this->user->id, ['invoice_serie_id' => $this->row->id]);

        return $this->row;
    }

    /**
     * @param string $css
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return string
     */
    public function validate(string $css): string
    {
        foreach ($this->ids as $id) {
            if (strpos($css, '#'.$id) === false) {
                throw new ValidatorException(__('validator.css-format'));
            }
        }

        return $css;
    }
}
