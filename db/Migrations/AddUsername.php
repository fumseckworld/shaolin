<?php


namespace Base\Migrations;


use Eywa\Database\Migration\Migration;;

class AddUsername extends Migration
{
    public static string $generared_at = '2020-02-12-12-22-13';

    public static string $table = 'users';

    public function up(): bool
    {
        echo '<p>add username</p>';
        return true;
    }

    public function down(): bool
    {
        echo '<p>remove username</p>';
        return true;
    }
}