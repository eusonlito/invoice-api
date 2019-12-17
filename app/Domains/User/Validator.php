<?php declare(strict_types=1);

namespace App\Domains\User;

use App\Domains\ValidatorAbstract;

class Validator extends ValidatorAbstract
{
    /**
     * @return array
     */
    protected static function configAuthCredentials(): array
    {
        return [
            'all' => false,

            'rules' => [
                'user' => 'required|email|disposable_email',
                'password' => 'required',
            ],

            'messages' => [
                'user.required' => __('validator.user-required'),
                'user.email' => __('validator.user-email'),
                'user.disposable_email' => __('validator.user-disposable_email'),
                'password.required' => __('validator.password-required'),
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configConfirmStart(): array
    {
        return [
            'all' => false,

            'rules' => [
                'user' => 'required|email|disposable_email',
            ],

            'messages' => [
                'user.required' => __('validator.user-required'),
                'user.email' => __('validator.user-email'),
                'user.disposable_email' => __('validator.user-disposable_email'),
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configUpdateProfile(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required',
                'user' => 'required|email|disposable_email',
                'password_current' => 'required|password'
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'user.required' => __('validator.user-required'),
                'user.email' => __('validator.user-email'),
                'user.disposable_email' => __('validator.user-disposable_email'),
                'password_current.required' => __('validator.password_current-required'),
                'password_current.password' => __('validator.password_current-password'),
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configUpdatePassword(): array
    {
        return [
            'all' => false,

            'rules' => [
                'password_current' => 'required|password',
                'password' => 'required|min:6',
                'password_repeat' => 'required|same:password',
            ],

            'messages' => [
                'password_current.required' => __('validator.password_current-required'),
                'password_current.password' => __('validator.password_current-password'),
                'password.required' => __('validator.password-required'),
                'password.min' => __('validator.password-min'),
                'password_repeat.required' => __('validator.password_repeat-required'),
                'password_repeat.same' => __('validator.password_repeat-same'),
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configPasswordResetFinish(): array
    {
        return [
            'all' => false,

            'rules' => [
                'password' => 'required|min:6',
                'password_repeat' => 'required|same:password'
            ],

            'messages' => [
                'password.required' => __('validator.password-required'),
                'password.min' => __('validator.password-min'),
                'password_repeat.same' => __('validator.password_repeat-same')
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configPasswordResetStart(): array
    {
        return [
            'all' => false,

            'rules' => [
                'user' => 'required|email|disposable_email',
            ],

            'messages' => [
                'user.required' => __('validator.user-required'),
                'user.email' => __('validator.user-email'),
                'user.disposable_email' => __('validator.user-disposable_email'),
            ]
        ];
    }

    /**
     * @return array
     */
    protected static function configSignup(): array
    {
        return [
            'all' => false,

            'rules' => [
                'name' => 'required',
                'user' => 'required|email|disposable_email',
                'password' => 'required|min:6',
                'password_repeat' => 'required|same:password',
                'conditions' => 'required|accepted'
            ],

            'messages' => [
                'name.required' => __('validator.name-required'),
                'user.required' => __('validator.user-required'),
                'user.email' => __('validator.user-email'),
                'user.disposable_email' => __('validator.user-disposable_email'),
                'password.required' => __('validator.password-required'),
                'password.min' => __('validator.password-min'),
                'password_repeat.required' => __('validator.password_repeat-required'),
                'password_repeat.same' => __('validator.password_repeat-same'),
                'conditions.required' => __('validator.conditions-required'),
                'conditions.accepted' => __('validator.conditions-required'),
            ]
        ];
    }
}
