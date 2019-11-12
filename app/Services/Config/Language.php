<?php declare(strict_types=1);

namespace App\Services\Config;

use Illuminate\Http\Request;
use App\Models;

class Language
{
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public static function byRequest(Request $request): void
    {
        static::set(static::byHeader((string)$request->header('Accept-Language')));
    }

    /**
     * @param \App\Models\User $user
     *
     * @return void
     */
    public static function byUser(Models\User $user): void
    {
        static::set($user->language);
    }

    /**
     * @param string $header
     *
     * @return \App\Models\Language
     */
    protected static function byHeader(string $header): Models\Language
    {
        $iso = preg_split('/[^a-zA-Z]/', $header)[0];

        return cache()->tags('language')->remember('language|'.$iso, 3600, static fn () => static::byHeaderCached($iso));
    }

    /**
     * @param string $iso
     *
     * @return \App\Models\Language
     */
    protected static function byHeaderCached(string $iso): Models\Language
    {
        if ($iso && ($exists = Models\Language::enabled()->where('iso', $iso)->first())) {
            return $exists;
        }

        return Models\Language::enabled()->where('default', 1)->first();
    }

    /**
     * @param \App\Models\Language $language
     *
     * @return void
     */
    protected static function set(Models\Language $language): void
    {
        app()->setLocale($language->iso);
        app()->singleton('language', static fn () => $language);
    }
}
