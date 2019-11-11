<?php declare(strict_types=1);

namespace Database\Seeds;

use App\Models;

class Configuration extends SeederAbstract
{
    /**
     * @return void
     */
    public function run()
    {
        $this->configuration();
    }

    /**
     * @return void
     */
    protected function configuration()
    {
        $this->truncate('configuration');

        Models\Configuration::insert([
            [
                'key' => 'user_not_confirmed_limit_days',
                'value' => 3,
                'description' => 'Límite de días para confirmar la cuenta de usuario',
                'public' => false,
            ],

            [
                'key' => 'user_not_confirmed_limit_days_delete',
                'value' => 10,
                'description' => 'Límite de días a partir de los que se borrarán las cuenta de usuario no confirmadas',
                'public' => false,
            ],

            [
                'key' => 'password_reset_expire',
                'value' => 48,
                'description' => 'Límite de horas en el que expirará el mail de recuperación de contraseña',
                'public' => false,
            ]
        ]);
    }
}
