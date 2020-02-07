<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use Illuminate\Support\Facades\Hash;
use App\Domains\User\Mail;
use App\Exceptions\ValidatorException;
use App\Models;
use App\Models\User as Model;
use App\Services\Request\IpLock;

class PasswordReset extends StoreAbstract
{
    /**
     * @throws \App\Exceptions\ValidatorException
     *
     * @return ?\App\Models\User
     */
    public function start(): ?Model
    {
        IpLock::locked(true);

        $this->row = Model::where('user', $this->data['user'])->enabled()->first();

        if ($this->row === null) {
            return null;
        }

        Models\UserPasswordReset::byUser($this->row)->available()->update([
            'canceled_at' => date('Y-m-d H:i:s')
        ]);

        $reset = Models\UserPasswordReset::create([
            'hash' => uniqidReal(16),
            'ip' => request()->ip(),
            'user_id' => $this->row->id
        ]);

        $this->cacheFlush();

        service()->log('user', 'password-reset-start', $this->row->id);

        Mail::passwordResetStart($this->row, $reset);

        return $this->row;
    }

    /**
     * @param string $hash
     *
     * @throws \App\Exceptions\ValidatorException
     *
     * @return \App\Models\User
     */
    public function finish(string $hash): Model
    {
        $reset = Models\UserPasswordReset::where('hash', $hash)->firstOrFail();

        $this->row = $this->finishUser($reset);

        $this->row->password = Hash::make($this->data['password']);
        $this->row->save();

        $reset->finished_at = date('Y-m-d H:i:s');
        $reset->save();

        $this->cacheFlush();

        service()->log('user', 'password-reset-finish', $this->row->id);

        return $this->row;
    }

    /**
     * @param \App\Models\UserPasswordReset $reset
     *
     * @return \App\Models\User
     */
    protected function finishUser(Models\UserPasswordReset $reset): Model
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
