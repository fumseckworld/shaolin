<?php


namespace Base\Migrations;


use Eywa\Database\Migration\Migration;;

class CreateAuthTable extends Migration
{
    public static string $created_at = '2020-02-17-18-37-01';

    public static string $table = 'auth';

    public static string $up_success_message = 'The auth table has been created successfully';

    public static string $up_error_message = 'The auth table creation has fail';

    public static string $down_success_message = 'The auth table has been deleted successfully';

    public static string $down_error_message = 'The deletion of the auth table has fail';

    public static string $up_title = 'Creating the auth table';

    public static string $down_title = 'Removing the auth table';

    public function up(): bool
    {
      return  $this->add('id','primary')->add('name','string',255,'UNIQUE','NOT NULL')->add('email','string',255,'UNIQUE','NOT NULL')->add('password','string',255,'NOT NULL')->create();
    }

    public function down(): bool
    {
        return $this->drop(static::$table);

    }
}