<?php declare(strict_types=1);

namespace App\Domains\Form;

use App\Domains\RepositoryAbstract;

class Repository extends RepositoryAbstract
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
        return $this->store(null, $this->validator('contact'))->contact();
    }
}
