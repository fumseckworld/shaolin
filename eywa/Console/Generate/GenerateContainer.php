<?php

namespace Eywa\Console\Generate {


    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateContainer extends Command
    {
        protected static $defaultName = 'make:container';

        protected function configure(): void
        {
            $this

                ->setDescription('Create a new container')
                ->addArgument('container', InputArgument::REQUIRED, 'The container name')
                ->addArgument('directory', InputArgument::OPTIONAL, 'The directory to create the container');
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


            $container = strval($input->getArgument('container'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $container) !== 1) {
                $io->error('You must use snake case syntax to generate the container');
                return  1;
            }

            $class = collect(explode('_', $container))->for('ucfirst')->join('');


            $dir = ucfirst(strval($input->getArgument('directory'))) ?? '';

            $namespace = def($dir) ? "Ioc\\$dir" : 'Ioc';


            $file  =  base('ioc', $dir, "$class.php");


            if (file_exists($file)) {
                $io->error(sprintf('The %s container already exist', $class));
                return 1;
            }

            $io->title('Generation of the container');

            if (!is_dir(base('ioc', $dir)) && def($dir)) {
                $io->title('Creating the directory');

                if (mkdir(base('ioc', $dir))) {
                    $io->success('Directory has been created successfully');
                }
            }

            if (file_exists($file)) {
                $io->error(sprintf('The %s container already exist', $class));

                return 1;
            }

            if (
                (new File("$file", EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace $namespace;

use Eywa\Ioc\Container;
use Eywa\Ioc\Ioc;

class $class extends Container
{

    /**
     * @inheritDoc
     */
    public function add(): Ioc
    {
        return \$this;
    }
}
")->flush()
            ) {
                $io->success(sprintf('The %s container has been generated successfully', $class));

                return 0;
            }
            $io->error(sprintf('The %s container generation has failed', $class));
            return 1;
        }
    }
}
