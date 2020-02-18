<?php


namespace Base\Migrations;


use Eywa\Database\Migration\Migration;;

class CreateUsersTable extends Migration
{
    public static string $created_at = '2020-02-12-12-20-42';

    public static string $table = 'users';

    public static string $up_success_message = 'The %s table has been created successfully';

    public static string $up_error_message = 'The table %s has not been created';

    public static string $down_success_message = 'The %s table was removed successfully';

    public static string $down_error_message = 'The %s table not exist';

    public static string $up_title = 'Creation of the %s table';

    public static string $down_title = 'Removing the %s table';

    public function up(): bool
    {
      return  $this->add('id','primary')->add('username','longtext')->add('email','string',255)->create();
    }

    public function down(): bool
    {
        return $this->drop(static::$table);

    }
}