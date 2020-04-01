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

        protected function configure(): void
        {
            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new model')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:model model table')
                ->addArgument('model', InputArgument::REQUIRED, 'The model name.')
                ->addArgument('table', InputArgument::REQUIRED, 'The table name.')
                ->addArgument('directory', InputArgument::OPTIONAL, 'The directory name.');
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

            $x = strval($input->getArgument('model'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $x) !== 1) {
                $io->error('You must use snake case syntax to generate the model');
                return  1;
            }

            $dir = ucfirst(strval($input->getArgument('directory')));
            if (def($dir)) {
                if (!is_dir(base('app', 'Models', $dir))) {
                    mkdir(base('app', 'Models', $dir));
                }
            }
            $table = strval($input->getArgument('table'));

            $model = collect(explode('_', $x))->for('ucfirst')->join('');

            $namespace = def($dir)
                ? 'App\Models\\' . $dir
                : 'App\\Models';

            $file = def($dir) ? base('app', 'Models', $dir, "$model.php") : base('app', 'Models', "$model.php");

            if (file_exists($file)) {
                $io->error(sprintf('The %s model already exist', $model));

                return 1;
            }
            $io->title('Generation of the model');
            if (
                (new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace
{

    use Eywa\Database\Model\Model;

    class $model extends Model
    {
        protected static string \$table = '$table';

        protected static int \$limit = 20;
    }

}\n")->flush()
            ) {
                $io->success(sprintf('The %s model has been generated successfully', $model));

                return 0;
            }
            $io->error(sprintf('The %s model generation has failed', $model));
            return 1;
        }
    }
}
