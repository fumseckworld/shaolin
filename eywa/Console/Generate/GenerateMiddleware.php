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

        protected function configure()
        {

            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new middleware')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:middleware name')->addArgument('middleware', InputArgument::REQUIRED, 'The middleware name.');
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

            $middleware = $input->getArgument('middleware');

            if(preg_match("#^[a-z]([a-z_]+)$#",$middleware) !== 1)
            {
                $io->error('You must use snake case syntax to generate the middleware');
                return  1;
            }

            $class = collect(explode('_',$middleware))->for('ucfirst')->join('');

            $file  =  base('app','Middleware',"$class.php");

            if (file_exists($file))
            {
                $io->error('The middleware already exist');

                return 1;
            }
            if ((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php


namespace App\Middleware {

    use Eywa\Http\Middleware\Middleware;
    use Eywa\Http\Request\ServerRequest;
    use Eywa\Http\Response\Response;

    class $class extends Middleware
    {
    
        /**
         * @inheritDoc
         */
        public function check(ServerRequest \$request): Response
        {
            // TODO: Implement check() method.
        }
    }
}")->flush()) {
                $io->success('The middleware was generated successfully');

                return 0;
            }

            $io->error('Failed to generate the middleware');
            return 1;
        }

    }
}