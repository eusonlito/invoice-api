<?php declare(strict_types=1);

namespace App\Services\Model\InvoiceConfiguration;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use App\Models\InvoiceConfiguration as Model;
use App\Services;
use App\Services\Model\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->model()->where('public', true)->pluck('value', 'key')->toArray();
    }

    /**
     * @return array
     */
    public function indexCached(): array
    {
        return $this->cache(__METHOD__, fn () => $this->index());
    }

    /**
     * @return string
     */
    public function css(): string
    {
        return Value::cssByCompany($this->user->company);
    }

    /**
     * @return string
     */
    public function cssPreview(): string
    {
        $css = (string)$this->request->input('css');

        StoreCss::validate($css);

        return Services\Pdf\Pdf::binary((string)view('pdf.pages.invoice.preview', [
            'css' => $css
        ]));
    }

    /**
     * @return string
     */
    public function cssCached(): string
    {
        return $this->cache(__METHOD__, fn () => $this->css());
    }

    /**
     * @return array
     */
    public function update(): array
    {
        return $this->store($this->request->input())->update()->toArray();
    }

    /**
     * @return string
     */
    public function cssUpdate(): string
    {
        return $this->store($this->validator('css'))->update(['css'])->get('css');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function model(): Builder
    {
        return Model::byCompany($this->user->company);
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function validator(string $name): array
    {
        return Validator::validate($name, $this->request->all());
    }

    /**
     * @param array $data = []
     *
     * @return \App\Services\Model\InvoiceConfiguration\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store($this->user, $data));
    }
}
