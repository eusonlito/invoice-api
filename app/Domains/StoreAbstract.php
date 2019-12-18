<?php declare(strict_types=1);

namespace App\Domains;

use App\Models\ModelAbstract;
use App\Models\User;

abstract class StoreAbstract
{
    use CacheTrait;

    /**
     * @var ?\App\Models\User
     */
    protected ?User $user;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param ?\App\Models\User $user = null
     * @param ?\App\Models\ModelAbstract $row = null
     * @param array $data = []
     *
     * @return self
     */
    public function __construct(?User $user = null, ?ModelAbstract $row = null, array $data = [])
    {
        $this->user = $user;
        $this->row = $row;
        $this->data = $data;
    }

    /**
     * @param string $class
     *
     * @return self
     */
    final protected function factory(string $class): self
    {
        return new $class($this->user, $this->row, $this->data);
    }
}
