<?php declare(strict_types=1);

namespace App\Services\Jwt;

use Illuminate\Http\Request;
use App\Models;

class Auth
{
    /**
     * @param array $credentials
     *
     * @return ?string
     */
    public static function byCredentials(array $credentials): ?string
    {
        if ($token = auth()->attempt($credentials)) {
            return static::byToken($token);
        }

        return null;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public static function byUser(Models\User $user): string
    {
        return static::byToken(static::tokenFromUser($user));
    }

    /**
     * @param string $token
     *
     * @return string
     */
    public static function byToken(string $token): string
    {
        static::invalidate();

        auth()->setToken($token)->authenticate();

        return (string)$token;
    }

    /**
     * @return void
     */
    public static function logout()
    {
        static::invalidate();
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @throws \App\Exceptions\AuthenticationException
     *
     * @return Models\User
     */
    public static function authByRequest(Request $request): Models\User
    {
        return auth()->authenticate(static::tokenFromRequest($request));
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
     * @param \Illuminate\Http\Request $request
     *
     * @return ?string
     */
    public static function tokenFromRequest(Request $request): ?string
    {
        if (!$request->header('Authorization') && ($bearer = $request->input('bearer'))) {
            $request->headers->set('Authorization', 'Bearer '.$bearer);
        }

        return auth()->setRequest($request)->getToken();
    }

    /**
     * @param \App\Models\User $user
     *
     * @return string
     */
    public static function tokenFromUser(Models\User $user): string
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
     * @return ?string
     */
    public static function refresh(Request $request): ?string
    {
        $token = auth()->setRequest($request)->parseToken()->refresh();

        if (empty($token)) {
            return null;
        }

        auth()->setToken($token);

        $request->headers->set('Authorization', 'Bearer '.$token);

        return $token;
    }
}
