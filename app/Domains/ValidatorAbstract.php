<?php declare(strict_types=1);

namespace App\Domains;

use App\Exceptions\UnexpectedValueException;
use App\Services\Validator\Generator;

abstract class ValidatorAbstract extends Generator
{
    /**
     * @param string $name
     *
     * @return array
     */
    final protected static function config(string $name): array
    {
        $method = 'config'.ucfirst($name);

        if (method_exists(get_called_class(), $method) === false) {
            throw new UnexpectedValueException($name);
        }

        return static::$method();
    }
}
