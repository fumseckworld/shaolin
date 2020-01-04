<?php

declare(strict_types=1);


namespace Eywa\Database\Migration {


    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Query\Sql;
    use Eywa\Database\Table\Table;
    use Faker\Generator;

    class Migration  implements   Migrate
    {

        /**
         * @inheritDoc
         */
        public function for(string $table): Migrate
        {
            // TODO: Implement for() method.
        }

        /**
         * @inheritDoc
         */
        public function add(string $column, string $type, array $options = []): Migrate
        {
            // TODO: Implement add() method.
        }

        /**
         * @inheritDoc
         */
        public function drop(): bool
        {
            // TODO: Implement drop() method.
        }

        /**
         * @inheritDoc
         */
        public function rename_table(string $new_name): bool
        {
            // TODO: Implement rename_table() method.
        }

        /**
         * @inheritDoc
         */
        public function rename_column(string $new_name): bool
        {
            // TODO: Implement rename_column() method.
        }

        /**
         * @inheritDoc
         */
        public function connect(): Connect
        {
            // TODO: Implement connect() method.
        }

        /**
         * @inheritDoc
         */
        public function del(string ...$columns): bool
        {
            // TODO: Implement del() method.
        }

        public function up(): bool
        {
            // TODO: Implement up() method.
        }

        public function down(): bool
        {
            // TODO: Implement down() method.
        }

        /**
         * @inheritDoc
         */
        public function columns(): array
        {
            // TODO: Implement columns() method.
        }

        /**
         * @inheritDoc
         */
        public function seed(int $records): bool
        {
            // TODO: Implement seed() method.
        }

        /**
         * @inheritDoc
         */
        public function generate(Generator $generator, Table $table): string
        {
            // TODO: Implement generate() method.
        }
    }
}