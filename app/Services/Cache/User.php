<?php declare(strict_types=1);

namespace App\Services\Cache;

use App\Models\User as Model;

class User
{
    /**
     * @const
     */
    protected const PATH = 'cache';

    /**
     * @var ?\App\Models\User
     */
    protected ?Model $user;

    /**
     * @var string
     */
    protected string $tag = '';

    /**
     * @var string
     */
    protected string $version = '';

    /**
     * @param ?\App\Models\User $user
     *
     * @return self
     */
    public function __construct(?Model $user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function tag(): string
    {
        if ($this->tag) {
            return $this->tag;
        }

        return $this->tag = $this->user ? ($this->user->id.'-'.(int)$this->user->company_id) : 'default';
    }

    /**
     * @return string
     */
    public function version(): string
    {
        if ($this->version) {
            return $this->version;
        }

        return $this->version = $this->get() ?: $this->set();
    }

    /**
     * @return string
     */
    public function refresh(): string
    {
        return $this->version = $this->set();
    }

    /**
     * @return ?string
     */
    protected function get(): ?string
    {
        if (empty($this->user)) {
            return $this->default();
        }

        $disk = service()->disk();
        $file = $this->path();

        return $disk->exists($file) ? $disk->get($file) : null;
    }

    /**
     * @return string
     */
    protected function set(): string
    {
        service()->disk()->put($this->path(), $version = $this->new());

        return $version;
    }

    /**
     * @return string
     */
    protected function default(): string
    {
        return config('cache.version');
    }

    /**
     * @return string
     */
    protected function path(): string
    {
        return static::PATH.'/'.$this->tag();
    }

    /**
     * @return string
     */
    protected function new(): string
    {
        return $this->default().'-'.uniqid();
    }
}
