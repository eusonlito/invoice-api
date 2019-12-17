<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use Illuminate\Support\Facades\Hash;
use App\Exceptions\ValidatorException;
use App\Models\User as Model;

class Profile extends StoreAbstract
{
    /**
     * @return \App\Models\User
     */
    public function profile(): Model
    {
        service()->backup('user-update-profile', $this->row, $this->row->id);

        if (Model::where('id', '!=', $this->row->id)->where('user', $this->data['user'])->first()) {
            throw new ValidatorException(__('validator.user-exists'));
        }

        if ($this->row->user !== $this->data['user']) {
            $this->row->confirmed_at = null;
        }

        $this->row->name = $this->data['name'];
        $this->row->user = $this->data['user'];

        $this->row->save();

        service()->log('user', 'user-update-profile', $this->row->id);

        return $this->row;
    }

    /**
     * @return \App\Models\User
     */
    public function password(): Model
    {
        $this->row->password = Hash::make($this->data['password']);
        $this->row->save();

        service()->log('user', 'user-update-password', $this->row->id);

        return $this->row;
    }
}
