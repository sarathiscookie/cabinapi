<?php

namespace App\Providers;


use Illuminate\Auth\EloquentUserProvider as BaseUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;


class CustomUserProvider extends BaseUserProvider
{
    /**
     * Create a new database user provider.
     *
     * @param string $model
     *
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = md5('aFGQ475SDsdfsaf2342' . $credentials['password'] . $user->usrPasswordSalt);
        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}
