<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use Illuminate\Contracts\Encryption\DecryptException;
use App\Exceptions;
use App\Models\User as Model;
use App\Domains\User\Mail;

class Confirm extends StoreAbstract
{
    /**
     * @throws \App\Exceptions\NotAllowedException
     *
     * @return \App\Models\User
     */
    public function check(): Model
    {
        if ($this->row->confirmed_at) {
            return $this->row;
        }

        $limit = (int)app('configuration')->get('user_not_confirmed_limit_days');

        if ($limit === 0) {
            return $this->row;
        }

        if ($this->row->created_at < date('Y-m-d H:i:s', strtotime('-'.$limit.' days'))) {
            throw new Exceptions\NotAllowedException(__('exception.user-not-confirmed'), null, null, 'user_not_confirmed');
        }

        return $this->row;
    }

    /**
     * @throws \App\Exceptions\ValidatorException
     *
     * @return \App\Models\User
     */
    public function start(): Model
    {
        $this->row = Model::where('user', $this->data['user'])->enabled()->firstOrFail();

        Mail::confirmStart($this->row);

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
        try {
            $id = (int)explode('|', decrypt($hash))[0];
        } catch (DecryptException $e) {
            throw new Exceptions\ValidatorException(__('exception.not-allowed'));
        }

        $this->row = Model::byId($id)->enabled()->firstOrFail();

        if ($this->row->confirmed_at) {
            return $this->row;
        }

        $this->row->confirmed_at = date('Y-m-d H:i:s');
        $this->row->save();

        service()->log('user', 'confirm', $this->row->id);

        return $this->row;
    }
}
