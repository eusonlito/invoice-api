<?php declare(strict_types=1);

namespace App\Domains\User;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\ValidatorException;
use App\Models;
use App\Models\User as Model;
use App\Services\Request\IpLock;
use App\Services\Mail\Mailer;

class StorePasswordReset
{
    /**
     * @param string $user
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return ?\App\Models\User
     */
    public static function start(string $user): ?Model
    {
        IpLock::locked(true);

        $user = Model::where('user', $user)->enabled()->first();

        if ($user === null) {
            return null;
        }

        Models\UserPasswordReset::byUser($user)->available()->update([
            'canceled_at' => date('Y-m-d H:i:s')
        ]);

        $reset = Models\UserPasswordReset::create([
            'hash' => uniqidReal(16),
            'ip' => request()->ip(),
            'user_id' => $user->id
        ]);

        service()->log('user', 'password-reset-start', $user->id);

        Mailer::queue(new Mail\PasswordReset($user, $reset), $user, [$user->user]);

        return $user;
    }

    /**
     * @param string $hash
     * @param string $password
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return \App\Models\User
     */
    public static function finish(string $hash, string $password): Model
    {
        $reset = Models\UserPasswordReset::where('hash', $hash)->firstOrFail();
        $user = static::finishUser($reset);

        $user->password = Hash::make($password);
        $user->save();

        $reset->finished_at = date('Y-m-d H:i:s');
        $reset->save();

        service()->log('user', 'password-reset-finish', $user->id);

        return $user;
    }

    /**
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return \App\Models\User
     */
    protected static function finishUser(Models\UserPasswordReset $reset): Model
    {
        if ($reset->finished_at || $reset->canceled_at) {
            throw new ValidatorException(__('exception.password-reset-executed'));
        }

        $hours = app('configuration')->get('password_reset_expire');
        $expire = date('Y-m-d H:i:s', strtotime('-'.$hours.' hours'));

        if ($reset->created_at < $expire) {
            throw new ValidatorException(__('exception.password-reset-expired', ['hours' => $hours]));
        }

        if ($reset->user->deleted_at || empty($reset->user->enabled)) {
            throw new ValidatorException(__('exception.user-not-found'));
        }

        return $reset->user;
    }
}
