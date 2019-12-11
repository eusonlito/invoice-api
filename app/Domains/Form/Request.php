<?php declare(strict_types=1);

namespace App\Domains\Form;

use Illuminate\Database\Eloquent\Builder;
use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @return array
     */
    public function contact(): array
    {
        return $this->store($this->validator('contact'))->contact();
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
     * @return \App\Domains\Form\Store
     */
    protected function store(array $data = []): Store
    {
        return $this->store ?? ($this->store = new Store(null, $data));
    }
}
