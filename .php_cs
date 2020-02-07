<?php declare(strict_types=1);

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

return Config::create()
    ->setRiskyAllowed(true)
    ->setRules(['native_constant_invocation' => true, 'native_function_invocation' => true])
    ->setFinder(Finder::create()->exclude(['phpunit', 'tests'])->in('vendor'));
