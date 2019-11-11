<?php declare(strict_types=1);

namespace App\Services\Model;

use App\Models;

abstract class StoreAbstract
{
    /**
     * @var ?\App\Models\User
     */
    protected ?Models\User $user;

    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var \App\Models\ModelAbstract
     */
    protected Models\ModelAbstract $row;

    /**
     * @param ?\App\Models\User $user
     * @param array $data = []
     *
     * @return self
     */
    public function __construct(?Models\User $user, array $data = [])
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    protected function cacheFlush(string $name)
    {
        cache()->tags($name)->flush();
    }

    /**
     * @param string $name
     * @param mixed $response
     *
     * @return void
     */
    protected function cacheFlushResponse(string $name, $response)
    {
        $this->cacheFlush($name);

        return $response;
    }
}
