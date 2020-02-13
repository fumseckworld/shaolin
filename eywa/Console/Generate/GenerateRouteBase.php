<?php

    namespace Eywa\Console\Generate {


        use DI\DependencyException;
        use DI\NotFoundException;
        use Eywa\Exception\Kedavra;
        use Eywa\Http\Routing\Admin;
        use Eywa\Http\Routing\Task;
        use Eywa\Http\Routing\Web;
        use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class GenerateRouteBase extends Command
		{
			
			protected static $defaultName = 'route:configure';
			
			protected function configure()
			{
				
				$this->setDescription('Generate all routes bases');
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             *
             * @return bool|int|null
             * @throws DependencyException
             * @throws Kedavra
             * @throws NotFoundException
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $io = new SymfonyStyle($input,$output);

                $io->title('Starting route configuration');

                if (!is_dir(base('routes')))
			        mkdir(base('routes'));


                if (file_exists(base('routes','web.sqlite3')))
                {
                    $io->error('The configuration wizard has been already executed');
                    return 1;
                }
                if (Web::generate())
                {
                    $io->success('The configuration has been generated successfully');
                    return  0;
                }

                $io->error('The base generation failed');

                return 1;

			}
			
		}
	}
