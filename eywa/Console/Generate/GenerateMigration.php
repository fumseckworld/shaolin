<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateMigration extends Command
    {
        protected static $defaultName = 'make:migration';

        protected function configure():void
        {
            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new migration')
                ->addArgument('table', InputArgument::REQUIRED, 'The table name')
                ->addArgument('migration', InputArgument::REQUIRED, 'The migration name.');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $migration = strval($input->getArgument('migration'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $migration) !== 1) {
                $io->error('You must use snake case syntax to generate the migration');
                return  1;
            }

            $class = collect(explode('_', $migration))->for('ucfirst')->join('');

            $file  =  base('db', 'Migrations', "$class.php");

            if (file_exists($file)) {
                $io->error(sprintf('The %s migration already exist', $class));
                return 1;
            }
            $io->title('Generation of the migration');
            $table  = strval($input->getArgument('table'));
            $time = date('Y-m-d-H-i-s');


            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace Evolution\Migrations {


    use Eywa\Database\Migration\Migration;

    class $class extends Migration
    {
        public static string \$created_at = '$time';

        public static string \$table = '$table';

        public static string \$up_success_message = '';

        public static string \$up_error_message = '';

        public static string \$down_success_message = '';

        public static string \$down_error_message = '';

        public static string \$up_title = '';

        public static string \$down_title = '';

        public function up(): bool
        {

        }

        public function down(): bool
        {

        }
    }
}")->flush()) {
                $io->success(sprintf('The %s migration was successfully generated', $class));
                return 0;
            }

            $io->error('The creation of the migrations has failed');
            return 1;
        }
    }
}
