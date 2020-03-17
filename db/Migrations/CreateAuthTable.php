<?php


namespace Evolution\Migrations {


    use Eywa\Database\Migration\Migration;

    class CreateAuthTable extends Migration
    {
        public static string $created_at = '2020-02-17-18-37-01';

        public static string $table = 'auth';

        public static string $up_success_message = 'The %s table has been created successfully';

        public static string $up_error_message = 'The %s table creation has fail';

        public static string $down_success_message = 'The %s table has been deleted successfully';

        public static string $down_error_message = 'The deletion of the %s table has fail';

        public static string $up_title = 'Creating the %s table';

        public static string $down_title = 'Removing the %s table';

        public function up(): bool
        {
            return  $this->add('id', 'primary')->add('username', 'varchar', 255, ['UNIQUE','NOT NULL'])->add('email', 'varchar', 255, ['UNIQUE','NOT NULL'])->add('password', 'varchar', 255, ['NOT NULL'])->create();
        }

        public function down(): bool
        {
            return $this->drop();
        }
    }
}
