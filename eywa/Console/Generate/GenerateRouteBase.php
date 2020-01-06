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
		
		class GenerateRouteBase extends Command
		{
			
			protected static $defaultName = 'make:routes';
			
			protected function configure()
			{
				
				$this->setDescription('Generate all routes bases');
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             *
             * @return bool|int|null
             * @throws Kedavra
             *
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
			    if (!is_dir(base('routes')))
			        mkdir(base('routes'));

	            Web::generate();
	            Admin::generate();
	            Task::generate();

	            return 0;

			}
			
		}
	}
