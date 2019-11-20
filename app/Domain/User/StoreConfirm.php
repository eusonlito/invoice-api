<?php declare(strict_types=1);

namespace App\Domain\User;

use Illuminate\Contracts\Encryption\DecryptException;
use App\Exceptions;
use App\Models\User as Model;
use App\Services\Mail\Mailer;

class StoreConfirm
{
    /**
     * @param \App\Models\User $user
     *
     * @throws \App\Exceptions\NotAllowedException
     *
     * @return \App\Models\User
     */
    public static function check(Model $user): Model
    {
        if ($user->confirmed_at) {
            return $user;
        }

        $limit = (int)app('configuration')->get('user_not_confirmed_limit_days');

        if ($limit === 0) {
            return $user;
        }

        if ($user->created_at < date('Y-m-d H:i:s', strtotime('-'.$limit.' days'))) {
            throw new Exceptions\NotAllowedException(__('exception.user-not-confirmed'), null, null, 'user_not_confirmed');
        }

        return $user;
    }

    /**
     * @param string $user
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return \App\Models\User
     */
    public static function start(string $user): Model
    {
        $user = Model::where('user', $user)->enabled()->firstOrFail();

        Mailer::userConfirm($user, encrypt($user->id.'|'.microtime(true)));

        return $user;
    }

    /**
     * @param string $hash
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return \App\Models\User
     */
    public static function finish(string $hash): Model
    {
        try {
            $user_id = (int)explode('|', decrypt($hash))[0];
        } catch (DecryptException $e) {
            throw new Exceptions\ValidatorException(__('exception.not-allowed'));
        }

        $user = Model::byId($user_id)->enabled()->firstOrFail();

        if ($user->confirmed_at) {
            return $user;
        }

        $user->confirmed_at = date('Y-m-d H:i:s');
        $user->save();

        service()->log('user', 'confirm', $user->id);

        return $user;
    }
}
