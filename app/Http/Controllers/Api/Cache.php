<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Domains\Cache\Request;

class Cache extends ControllerAbstract
{
    /**
     * @const
     */
    protected const REQUEST = Request::class;

    /**
     * GET /cache/version
     *
     * @return \Illuminate\Http\Response
     */
    public function version(): Response
    {
        return response($this->request()->versionCached());
    }
}
