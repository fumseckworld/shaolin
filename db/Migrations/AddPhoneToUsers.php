<?php

namespace Evolution\Migrations {


    use Eywa\Database\Migration\Migration;

    class AddPhoneToUsers extends Migration
    {
        public static string $created_at = '2020-02-17-16-16-03';

        public static string $table = 'users';

        public static string $up_success_message = 'The phone column was added successfully in the %s table';

        public static string $up_error_message = 'Fail to add phone column in the %s table';

        public static string $down_success_message = 'The phone column was successfully deleted to the %s table';

        public static string $down_error_message = 'The phone column not exist';

        public static string $up_title = 'Adding the phone column to the %s table';

        public static string $down_title = 'Removing the phone column in the %s table';

        public function up(): bool
        {
            return  $this->add('phone', 'varchar', 255)->update();
        }

        public function down(): bool
        {
            return $this->remove(['phone']);
        }
    }
}
