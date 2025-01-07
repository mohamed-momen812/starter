<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }

    public function findbyToken($token)
    {
        return $this->model->where('remember_token', $token)->first();
    }

}
