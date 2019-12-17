<?php declare(strict_types=1);

namespace App\Services\Jwt;

use Illuminate\Http\Request;
use App\Models\User;

class Auth
{
    /**
     * @param array $credentials
     *
     * @return ?\App\Models\User
     */
    public static function byCredentials(array $credentials): ?User
    {
        if ($token = auth()->attempt($credentials)) {
            return static::byToken($token);
        }

        return null;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public static function byUser(User $user): User
    {
        return static::byToken(static::tokenFromUser($user));
    }

    /**
     * @param string $token
     *
     * @return \App\Models\User
     */
    public static function byToken(string $token): User
    {
        static::invalidate();

        return auth()->setToken($token)->authenticate();
    }

    /**
     * @return void
     */
    public static function logout()
    {
        static::invalidate();
    }

    /**
     * @return ?string
     */
    public static function token(): ?string
    {
        if ($user = auth()->user()) {
            return (string)auth()->tokenById($user->id);
        }

        return null;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public static function tokenFromUser(User $user): string
    {
        return (string)auth()->login($user);
    }

    /**
     * @return void
     */
    public static function invalidate()
    {
        if ($token = static::token()) {
            auth()->invalidate($token);
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return ?\App\Models\User
     */
    public static function refresh(Request $request): ?User
    {
        $token = auth()->setRequest($request)->parseToken()->refresh();

        if (empty($token)) {
            return null;
        }

        $request->headers->set('Authorization', 'Bearer '.$token);

        return auth()->setToken($token)->user();
    }
}
