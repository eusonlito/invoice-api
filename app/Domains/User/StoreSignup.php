<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\ValidatorException;
use App\Models\User as Model;
use App\Services\Mail\Mailer;

class StoreSignup
{
    /**
     * @param array $data
     *
     * @return \App\Models\User
     */
    public static function start(array $data): Model
    {
        return DB::transaction(static function () use ($data): Model {
            $data = static::data($data);
            $user = static::create($data);

            static::log($user);
            static::auth($user);
            static::mail($user);

            return $user;
        });
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected static function data(array $data): array
    {
        $data['user'] = strtolower($data['user']);
        $data['password'] = $data['password'] ?? '';

        return $data;
    }

    /**
     * @param array $data
     *
     * @return \App\Models\User
     */
    protected static function create(array $data): Model
    {
        if (Model::where('user', $data['user'])->count()) {
            throw new ValidatorException(__('validator.user-exists'));
        }

        return static::createFromData($data);
    }

    /**
     * @param array $data
     *
     * @return \App\Models\User
     */
    protected static function createFromData(array $data): Model
    {
        $data = static::defaultData($data);

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        return Model::create($data);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected static function defaultData(array $data): array
    {
        return [
            'user' => $data['user'],
            'name' => $data['name'],
            'password' => $data['password'],
            'enabled' => 1,
            'language_id' => app('language')->id
        ];
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public static function auth(Model $user): Model
    {
        StoreAuth::byUser($user);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public static function log(Model $user): Model
    {
        service()->log('user', 'signup', $user->id);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     *
     * @return \App\Models\User
     */
    public static function mail(Model $user): Model
    {
        Mailer::userSignup($user, encrypt($user->id.'|'.time()));

        return $user;
    }
}
