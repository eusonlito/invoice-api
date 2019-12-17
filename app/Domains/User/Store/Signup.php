<?php declare(strict_types=1);

namespace App\Domains\User\Store;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Domains\User\Mail;
use App\Domains\User\Store;
use App\Exceptions\ValidatorException;
use App\Models\User as Model;

class Signup extends StoreAbstract
{
    /**
     * @return \App\Models\User
     */
    public function start(): Model
    {
        return DB::transaction(function (): Model {
            $this->data();
            $this->create();
            $this->log();
            $this->auth();
            $this->mail();

            return $this->row;
        });
    }

    /**
     * @return self
     */
    protected function data(): self
    {
        $this->data['user'] = strtolower($this->data['user']);
        $this->data['password'] = $this->data['password'] ?? '';

        return $this;
    }

    /**
     * @return \App\Models\User
     */
    protected function create(): Model
    {
        if (Model::where('user', $this->data['user'])->count()) {
            throw new ValidatorException(__('validator.user-exists'));
        }

        return $this->row = $this->createFromData();
    }

    /**
     * @return \App\Models\User
     */
    protected function createFromData(): Model
    {
        $data = $this->defaultData();

        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        return Model::create($data);
    }

    /**
     * @return array
     */
    protected function defaultData(): array
    {
        return [
            'user' => $this->data['user'],
            'name' => $this->data['name'],
            'password' => $this->data['password'],
            'enabled' => 1,
            'language_id' => app('language')->id
        ];
    }

    /**
     * @return self
     */
    public function auth(): self
    {
        (new Store($this->row, $this->row))->authUser();

        return $this;
    }

    /**
     * @return self
     */
    public function log(): self
    {
        service()->log('user', 'signup', $this->row->id);

        return $this;
    }

    /**
     * @return self
     */
    public function mail(): self
    {
        Mail::signup($this->row);

        return $this;
    }
}
