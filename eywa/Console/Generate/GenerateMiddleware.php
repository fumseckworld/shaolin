<?php

namespace Eywa\Console\Generate {

    use Eywa\Exception\Kedavra;
    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateMiddleware extends Command
    {
        protected static $defaultName = 'make:middleware';

        protected function configure(): void
        {
            $this
                ->setDescription('Create a new middleware')
                ->setHelp('php shaolin make:middleware name')
                ->addArgument('middleware', InputArgument::REQUIRED, 'The middleware name.')
                ->addArgument('directory', InputArgument::OPTIONAL, 'The middleware directory.');
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

            $middleware = strval($input->getArgument('middleware'));

            if (preg_match("#^[a-z]([a-z_]+)$#", $middleware) !== 1) {
                $io->error('You must use snake case syntax to generate the middleware');
                return  1;
            }

            $class = collect(explode('_', $middleware))->for('ucfirst')->join('');

            $directory = ucfirst(strval($input->getArgument('directory')));

            if (def($directory)) {
                if (!is_dir(base('app', 'Middleware', $directory))) {
                    mkdir(base('app', 'Middleware', $directory));
                }
            }
            $file = def($directory) ? base('app', 'Middleware', $directory, "$class.php")
                :  base('app', 'Middleware', "$class.php");

            $namespace = def($directory) ? 'App\Middleware\\' . $directory : 'App\Middleware';

            if (file_exists($file)) {
                $io->error(sprintf('The %s middleware already exist', $class));

                return 1;
            }


            if (
                (new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace $namespace {

    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;

    class $class extends Middleware
    {
    
        /**
         * @inheritDoc
         */
        public function check(ServerRequest \$request): void
        {
            // TODO: Implement check() method.
        }
    }
}\n")->flush()
            ) {
                $io->success(sprintf('The %s middleware was generated successfully', $class));

                return 0;
            }

            $io->error('Failed to generate the middleware');
            return 1;
        }
    }
}
