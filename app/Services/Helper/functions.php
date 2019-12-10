<?php declare(strict_types=1);

/**
 * @param int $length
 *
 * @return string
 */
function uniqidReal(int $length): string
{
    return substr(bin2hex(random_bytes(intval($length / 2))), 0, $length);
}

/**
 * @param mixed $value
 * @param int $decimals = 2
 *
 * @return string
 */
function number($value, int $decimals = 2): string
{
    return number_format((float)$value, $decimals, ',', '.');
}

/**
 * @param mixed $value
 * @param int $decimals = 2
 *
 * @return string
 */
function money($value, int $decimals = 2): string
{
    return number($value, $decimals).'€';
}

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

if (!function_exists('dateToDate')) {
    /**
     * @param string $date
     * @param bool $with_time = false
     *
     * @return ?string
     */
    function dateToDate(string $date, bool $with_time = false): ?string
    {
        if (empty($date)) {
            return $date;
        }

        [$day, $time] = preg_split('#[\s\.T]#', $date) + ['', ''];

        if (strpos($day, ':')) {
            [$day, $time] = [$time, $day];
        }

        if (!preg_match('#^[0-9]{2,4}[/\-][0-9]{2}[/\-][0-9]{2,4}#', $day, $matches)) {
            return null;
        }

        $day = preg_split('#[/\-]#', $matches[0]);

        if (strlen($day[0]) !== 4) {
            $day = array_reverse($day);
        }

        $day = implode('-', $day);

        return $with_time ? trim($day.' '.$time) : $day;
    }
}

/**
 * @return \App\Services\Helper\Service
 */
function service(): App\Services\Helper\Service
{
    static $service;

    return $service ?: ($service = new App\Services\Helper\Service());
}
