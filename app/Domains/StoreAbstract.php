<?php declare(strict_types=1);

namespace App\Domains;

use App\Models;

abstract class StoreAbstract extends CacheAbstract
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
}
