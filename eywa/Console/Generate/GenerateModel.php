<?php

namespace Eywa\Console\Generate {

    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

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
            $x = $input->getArgument('model');
            $table = $input->getArgument('table');
            $model = ucfirst(strtolower($x));

            $namespace = 'App\Models';

            $file = 'app' . DIRECTORY_SEPARATOR . 'Models' . DIRECTORY_SEPARATOR . $model . '.php';

            if (file_exists($file))
            {
                $output->write("<bg=red;fg=white>The $model model already exist\n");

                return 1;
            }
            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace { \n\n\tuse Imperium\Model\Model;\n\n\tClass $x extends Model\n\t{\n\n\t\tprotected  \$table = '$table';\n\n\t\tprotected static  \$by = 'id';\n\n\t\tprotected static  \$limit = 20;\n\n}\n\n}\n")->flush()) {
                $output->write("<info>The $model model was generated successfully</info>\n");

                return 0;
            }

            return 1;
        }

    }
}