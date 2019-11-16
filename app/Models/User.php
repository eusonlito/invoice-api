<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends ModelAbstract implements AuthenticatableContract, JWTSubject
{
    use Authenticatable;

    /**
     * @var string
     */
    protected $table = 'user';

    /**
     * @var string
     */
    public static string $foreign = 'user_id';

    /**
     * @var array
     */
    protected $casts = [
        'enabled' => 'boolean',
    ];

    /**
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): Relations\BelongsTo
    {
        return $this->belongsTo(Company::class, Company::$foreign);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function language(): Relations\BelongsTo
    {
        return $this->belongsTo(Language::class, Language::$foreign);
    }
}
