<?php declare(strict_types=1);

namespace App\Services\Cache;

use Closure;
use Illuminate\Http\Request;
use App\Models\User as Model;

class Domain
{
    /**
     * @var int
     */
    protected int $ttl;

    /**
     * @var string
     */
    protected string $url;

    /**
     * @var \App\Services\Cache\User
     */
    protected User $user;

    /**
     * @param ?\Illuminate\Http\Request $request
     * @param ?\App\Models\User $user
     * @param int $ttl
     *
     * @return self
     */
    public function __construct(?Request $request, ?Model $user, int $ttl)
    {
        $this->ttl = $ttl;
        $this->url = $request ? $request->fullUrl() : '';
        $this->user = new User($user);

        return $this;
    }

    /**
     * @param string $name
     * @param Closure $closure
     *
     * @return mixed
     */
    public function get(string $name, Closure $closure)
    {
        return cache()
            ->tags($this->tags($name))
            ->remember($this->name($name), $this->ttl, $closure);
    }

    /**
     * @return string
     */
    public function version(): string
    {
        return $this->user->version();
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        cache()->tags($this->user->tag())->flush();

        $this->user->refresh();
    }

    /**
     * @param string $name
     *
     * @return array
     */
    protected function tags(string $name): array
    {
        return [$this->user->tag(), $this->tag($name)];
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function tag(string $name): string
    {
        return $this->prefix(explode('\\', $name)[2]);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function name(string $name): string
    {
        return md5($this->prefix($name).'|'.$this->url);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function prefix(string $name): string
    {
        return $this->user->version().'|'.$name;
    }
}
