<?php declare(strict_types=1);

namespace App\Domains\Cache;

use Illuminate\Http\Response;
use App\Domains\ControllerApiAbstract;

class ControllerApi extends ControllerApiAbstract
{
    /**
     * GET /cache/version
     *
     * @return \Illuminate\Http\Response
     */
    public function version(): Response
    {
        return response($this->cache(__METHOD__, fn () => $this->cacheVersion()));
    }
}
