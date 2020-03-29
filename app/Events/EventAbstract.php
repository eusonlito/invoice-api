<?php declare(strict_types=1);

namespace App\Events;

use Illuminate\Queue\SerializesModels;

abstract class EventAbstract
{
    use SerializesModels;
}
