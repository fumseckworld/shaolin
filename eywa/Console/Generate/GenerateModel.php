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

            $x = $input->getArgument('model');

            if(preg_match("#^[a-z]([a-z_]+)$#",$x) !== 1)
            {
                $io->error('You must use snake case syntax to generate the model');
                return  1;
            }

            $io->title('Generation of the model');

            $table = $input->getArgument('table');

            $model = collect(explode('_',$x))->for('ucfirst')->join('');

            $namespace = 'App\Models';

            $file = base( 'app' , 'Models' , "$model.php");

            if (file_exists($file))
            {
                $io->error("The $model model already exist");

                return 1;
            }
            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace\n{ \n\n\tuse Eywa\Database\Model\Model;\n\n\tClass $model extends Model\n\t{\n\n\t\tprotected static string \$table = '$table';\n\n\t\tprotected static string \$by = 'id';\n\n\t\tprotected static int \$limit = 20;\n\n\t}\n\n}\n")->flush()) {
                $io->success("The $model model has been generated successfully");

                return 0;
            }
            $io->error("The $model generation has failed");
            return 1;
        }

    }
}