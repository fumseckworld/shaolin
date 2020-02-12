<?php


namespace App\Database\Migrations;


use Eywa\Database\Migration\Migration;;

class CreateUsersTable extends Migration
{
    public static string $generared_at = '2020-02-12-12-20-42';

    public static string $table = 'users';

    public function up(): bool
    {

        echo '<p>create user table</p>';
        return true;



    }

    public function down(): bool
    {
        echo '<p>remove user table</p>';
        return  true;
    }
}