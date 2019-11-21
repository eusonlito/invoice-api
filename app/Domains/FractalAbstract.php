<?php declare(strict_types=1);

namespace App\Domains;

use Illuminate\Support\Collection;
use App\Models\ModelAbstract;

abstract class FractalAbstract
{
    /**
     * @param string $function
     * @param mixed $value
     * @param array ...$parameters
     *
     * @throws \InvalidArgumentException
     *
     * @return ?array
     */
    public static function transform(string $function, $value, ...$parameters): ?array
    {
        if ($value === null) {
            return null;
        }

        if (empty($value)) {
            return [];
        }

        if ($value instanceof Collection) {
            return static::collection($function, $value, $parameters);
        }

        if (static::isArraySequential($value)) {
            return static::sequential($function, $value, $parameters);
        }

        return static::call($function, $value, $parameters);
    }

    /**
     * @param mixed $value
     *
     * @return bool
     */
    protected static function isArraySequential($value): bool
    {
        return is_array($value) && (array_values($value) === $value);
    }

    /**
     * @param string $function
     * @param \Illuminate\Support\Collection $value
     * @param array $parameters
     *
     * @return array
     */
    protected static function collection(string $function, Collection $value, array $parameters): array
    {
        return $value->map(static fn ($value) => static::call($function, $value, $parameters))->values()->toArray();
    }

    /**
     * @param string $function
     * @param array $value
     * @param array $parameters
     *
     * @return array
     */
    protected static function sequential(string $function, array $value, array $parameters): array
    {
        return array_map(static fn ($value) => static::call($function, $value, $parameters), array_values($value));
    }

    /**
     * @param string $function
     * @param mixed $value
     * @param array $parameters
     *
     * @return ?array
     */
    protected static function call(string $function, $value, array $parameters): ?array
    {
        return forward_static_call_array(['static', $function], array_merge([$value], $parameters));
    }

    /**
     * @param \App\Models\ModelAbstract $row
     * @param string $name
     * @param string $class
     * @param string $view = 'simple'
     * @param mixed ...$params
     *
     * @return ?array
     */
    protected static function relationIfLoaded(
        ModelAbstract $row,
        string $name,
        string $class,
        string $view = 'simple',
        ...$params
    ): ?array {
        if ($row->relationLoaded($name) && $row->$name) {
            return $class::$view($row->$name, ...$params);
        }

        return null;
    }
}
