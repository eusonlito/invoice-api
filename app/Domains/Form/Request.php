<?php declare(strict_types=1);

namespace App\Domains\Form;

use App\Domains\RequestAbstract;

class Request extends RequestAbstract
{
    /**
     * @const string
     */
    protected const STORE = Store::class;

    /**
     * @const string
     */
    protected const VALIDATOR = Validator::class;

    /**
     * @return array
     */
    public function contact(): array
    {
        return $this->store($this->validator('contact'))->contact();
    }
}
