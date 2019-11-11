<?php declare(strict_types=1);

if (!function_exists('uniqidReal')) {
    /**
     * @param int $length
     *
     * @return string
     */
    function uniqidReal(int $length): string
    {
        return substr(bin2hex(random_bytes(intval($length / 2))), 0, $length);
    }
}

if (!function_exists('money')) {
    /**
     * @param mixed $value
     * @param int $decimals = 2
     *
     * @return string
     */
    function money($value, int $decimals = 2): string
    {
        return number_format((float)$value, $decimals, ',', '.').'€';
    }
}

if (!function_exists('routeWeb')) {
    /**
     * @param string $route
     * @param string|int|array $params
     *
     * @return string
     */
    function routeWeb(string $route, $params): string
    {
        return str_replace(config('app.url').'/v1', config('app.web'), route($route, $params));
    }
}

if (!function_exists('service')) {
    /**
     * @return \App\Services\Helper\Service
     */
    function service(): App\Services\Helper\Service
    {
        return new App\Services\Helper\Service();
    }
}
