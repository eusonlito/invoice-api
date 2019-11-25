<?php declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tests\TestAbstract as BaseTestAbstract;
use App\Models;

abstract class TestAbstract extends BaseTestAbstract
{
   /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user = null
     * @param mixed ...$params
     *
     * @return self
     */
    protected function route(string $name, ...$params): string
    {
        return (string)route($this->route.'.'.$name, $params);
    }

   /**
     * @param \Illuminate\Contracts\Auth\Authenticatable $user = null
     *
     * @return self
     */
    protected function auth(Authenticatable $user = null)
    {
        $user = $user ?: $this->user();

        $this->withHeader('Authorization', 'Bearer '.JWTAuth::fromUser($user));

        parent::actingAs($user);

        return $this;
    }

   /**
     * @param string $key
     * @param array $params = []
     *
     * @return string
     */
    protected function t(string $key, array $params = []): string
    {
        return trim(json_encode(__($key, $params)), '"');
    }

   /**
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function user(): Authenticatable
    {
        return Models\User::orderBy('id', 'DESC')->first();
    }

   /**
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function userFirst(): Authenticatable
    {
        return Models\User::orderBy('id', 'DESC')->skip(1)->first();
    }
}
