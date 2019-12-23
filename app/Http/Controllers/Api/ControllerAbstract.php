<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Domains\RequestAbstract;
use App\Http\Controllers\ControllerAbstract as BaseController;

abstract class ControllerAbstract extends BaseController
{
    /**
     * @return \App\Domains\RequestAbstract
     */
    protected function request(): RequestAbstract
    {
        $class = static::REQUEST;

        return new $class($this->request, $this->user);
    }
}
