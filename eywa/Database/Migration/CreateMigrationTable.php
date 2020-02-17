<?php


namespace Eywa\Database\Migration {


    class CreateMigrationTable extends Migration
    {

        public static string $table = 'migrations';

        public static string $success_message = 'The migrations table has been created successfully';

        public static string $error_message = 'The creation of the migrations table has fail';

        /**
         * @inheritDoc
         */
        public function up(): bool
        {
           return  $this->add('version','primary')->add('migration','string',255)->add('time','datetime')->create();
        }

        /**
         * @inheritDoc
         */
        public function down(): bool
        {
            return $this->drop('migrations');
        }
    }
}