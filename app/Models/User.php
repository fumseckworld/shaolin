<?php

namespace App\Models
{

    use Eywa\Database\Model\Model;

    class User extends Model
    {
        protected static string $table = 'auth';

        protected static int $limit = 20;
    }

}
