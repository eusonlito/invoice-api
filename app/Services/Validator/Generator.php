<?php declare(strict_types=1);

namespace App\Services\Validator;

use Exception;

abstract class Generator
{
    /**
     * @param string $name
     * @param array $input
     *
     * @return array
     */
    public static function validate(string $name, array $input): array
    {
        $config = static::config($name);

        try {
            return Validator::validate($input, $config['rules'], $config['messages'], $config['all']);
        } catch (Exception $e) {
            Error::set($e);
        }
    }
}
