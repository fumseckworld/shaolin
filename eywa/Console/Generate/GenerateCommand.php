<?php

namespace Eywa\Console\Generate {

    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateCommand extends Command
    {
        protected static $defaultName = 'make:console';

        protected function configure(): void
        {
            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new console')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:model model table')
                ->addArgument('console', InputArgument::REQUIRED, 'The console name')
                ->addArgument('name', InputArgument::REQUIRED, 'The console command')
                ->addArgument('description', InputArgument::REQUIRED, 'The comand description')
                ->addArgument('directory', InputArgument::OPTIONAL, 'The console directory');
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

            $x = strval($input->getArgument('console'));
            $command = strval($input->getArgument('name'));
            $description = strval($input->getArgument('description'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $x) !== 1) {
                $io->error('You must use snake case syntax to generate the console');
                return 1;
            }

            $directory = ucfirst(strval($input->getArgument('directory')));

            if (!is_dir(base('app', 'Console', $directory))) {
                mkdir(base('app', 'Console', $directory));
            }
            $io->title('Generation of the console');


            $console = collect(explode('_', $x))->for('ucfirst')->join('');

            $namespace = def($directory) ? 'App\Console\\' . $directory : 'App\Console';

            $file = def($directory)
                ?  base('app', 'Console', $directory, "$console.php")
                : base('app', 'Console', "$console.php");

            if (file_exists($file)) {
                $io->error(sprintf('The %s console already exist', $console));

                return 1;
            }
            if (
                (new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php

namespace $namespace;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class $console extends Command
{
    protected static \$defaultName = '$command';

    protected function configure(): void
    {
        \$this->setDescription('$description');
    }

    public function execute(InputInterface \$input, OutputInterface \$output): int
    {
        \$io = new SymfonyStyle(\$input, \$output);


        return 0;
    }
}\n")->flush()
            ) {
                $io->success(sprintf('The %s console has been created successfully', $console));

                return 0;
            }
            $io->error(sprintf('The %s console creation has failede', $console));
            return 1;
        }
    }
}
