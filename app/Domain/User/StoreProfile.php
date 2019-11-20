<?php declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\ValidatorException;
use App\Models\User as Model;

class StoreProfile
{
    /**
     * @param \App\Models\User $user
     * @param array $data
     *
     * @return \App\Models\User
     */
    public static function profile(Model $user, array $data): Model
    {
        service()->backup('user-update-profile', $user, $user->id);

        if (Model::where('id', '!=', $user->id)->where('user', $data['user'])->first()) {
            throw new ValidatorException(__('validator.user-exists'));
        }

        if ($user->user !== $data['user']) {
            $user->confirmed_at = null;
        }

        $user->name = $data['name'];
        $user->user = $data['user'];

        $user->save();

        service()->log('user', 'user-update-profile', $user->id);

        return $user;
    }

    /**
     * @param \App\Models\User $user
     * @param string $password
     *
     * @return \App\Models\User
     */
    public static function password(Model $user, string $password): Model
    {
        $user->password = Hash::make($password);
        $user->save();

        service()->log('user', 'user-update-password', $user->id);

        return $user;
    }
}
