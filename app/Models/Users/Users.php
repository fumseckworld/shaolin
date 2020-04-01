<?php

namespace App\Models\Users
{

    use Eywa\Database\Model\Model;

    class Users extends Model
    {
        protected static string $table = 'users';

        protected static int $limit = 20;
    }

}
