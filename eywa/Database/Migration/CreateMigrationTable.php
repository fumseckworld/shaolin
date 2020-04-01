<?php

namespace Eywa\Database\Migration {


    class CreateMigrationTable extends Migration
    {
        public static string $table = 'migrations';

        public static string $up_title = 'Creating the migrations table';

        public static string $up_success_message = 'The migrations table has been created successfully';

        public static string $up_error_message = 'The migrations table creation has failed';

        /**
         * @inheritDoc
         */
        public function up(): bool
        {
            return  $this->add('id', 'primary')
                    ->add('version', 'string', 255)
                    ->add('migration', 'string', 255)
                    ->add('time', 'datetime')
                    ->create();
        }

        /**
         * @inheritDoc
         */
        public function down(): bool
        {
            return $this->drop();
        }
    }
}
