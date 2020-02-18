<?php


namespace Base\Migrations;


use Eywa\Database\Migration\Migration;

class CreatePurchaseTable extends Migration
{
    public static string $created_at = '2020-02-18-06-36-51';

    public static string $table = 'achats';

    public static string $up_success_message = 'The %s table has been created successfully';

    public static string $up_error_message = 'The %s table creation has fail';

    public static string $down_success_message = 'The %s table has been deleted successfully';

    public static string $down_error_message = 'The deletion of the %s table has fail';

    public static string $up_title = 'Creating the %s table';

    public static string $down_title = 'Removing the %s table';

    public function up(): bool
    {
      return  $this->add('id','primary')->add('person','integer')->foreign('person','users','id','DELETE','CASCADE')->add('achats','string',255,'NOT NULL')->add('sold','integer',0,'NOT NULL')->create();
    }

    public function down(): bool
    {
        return $this->drop(static::$table);

    }
}