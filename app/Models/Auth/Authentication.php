<?php

namespace App\Models\Auth
{

    use Eywa\Database\Model\Model;

    class Authentication extends Model
    {
        protected static string $table = 'auth';

        protected static int $limit = 20;
    }

}
