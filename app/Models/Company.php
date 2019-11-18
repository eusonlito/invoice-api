<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations;

class Company extends ModelAbstract
{
    /**
     * @var string
     */
    protected $table = 'company';

    /**
     * @var string
     */
    public static string $foreign = 'company_id';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country(): Relations\BelongsTo
    {
        return $this->belongsTo(Country::class, Country::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): Relations\BelongsTo
    {
        return $this->belongsTo(User::class, User::$foreign);
    }
}
