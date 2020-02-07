<?php declare(strict_types=1);

namespace App\Domains;

use Illuminate\Http\Request;
use App\Models\ModelAbstract;
use App\Models\User;

abstract class StoreAbstract
{
    use CacheTrait;

    /**
     * @var \Illuminate\Http\Request
     */
    protected ?Request $request;

    /**
     * @var ?\App\Models\User
     */
    protected ?User $user;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @param ?\Illuminate\Http\Request $request = null
     * @param ?\App\Models\User $user = null
     * @param ?\App\Models\ModelAbstract $row = null
     * @param array $data = []
     *
     * @return self
     */
    final public function __construct(?Request $request = null, ?User $user = null, ?ModelAbstract $row = null, array $data = [])
    {
        $this->request = $request;
        $this->user = $user;
        $this->row = $row;
        $this->data = $data;

        $this->cacheLoad();
    }

    /**
     * @param string $class
     * @param array $data = []
     *
     * @return self
     */
    final protected function factory(string $class, array $data = []): self
    {
        return new $class($this->request, $this->user, $this->row, $data ?: $this->data);
    }
}
