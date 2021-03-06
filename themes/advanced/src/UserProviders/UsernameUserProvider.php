<?php

namespace Themes\SodaExample\UserProviders;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;


class UsernameUserProvider extends EloquentUserProvider {


    public function __construct(HasherContract $hasher, $model) {
        parent::__construct($hasher, $model);
    }

    /**
     * Validate a user against the given credentials. We're always valid with this userprovider.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable $user
     * @param  array $credentials
     *
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials) {
        return true;
    }
}
