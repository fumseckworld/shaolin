<?php

namespace Eywa\Console\Generate {

    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateModel extends Command
    {

        protected static $defaultName = 'make:model';

        protected function configure()
        {

            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new model')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:model model table')->addArgument('model', InputArgument::REQUIRED, 'The model name.')->addArgument('table', InputArgument::REQUIRED, 'The table name.');
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
            $io = new SymfonyStyle($input,$output);

            $io->title('Generation of the model');

            $x = $input->getArgument('model');
            $table = $input->getArgument('table');
            $model = ucfirst(strtolower($x));

            $namespace = 'App\Models';

            $file = base( 'app' , 'Models' , "$model.php");

            if (file_exists($file))
            {
                $io->error("The $model model already exist");

                return 1;
            }
            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace\n{ \n\n\tuse Eywa\Database\Model\Model;\n\n\tClass $model extends Model\n\t{\n\n\t\tprotected static string \$table = '$table';\n\n\t\tprotected static string \$by = 'id';\n\n\t\tprotected static int \$limit = 20;\n\n\t}\n\n}\n")->flush()) {
                $io->success("The $model model was generated successfully");

                return 0;
            }

            return 1;
        }

    }
}