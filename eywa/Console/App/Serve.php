<?php
	
	namespace Eywa\Console\App
	{

        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		
		class Serve extends Command
		{
			
			protected static $defaultName = "app:run";
			
			
			protected function configure()
			{
				$this->setDescription('Run a development server');
			}
		
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				$output->writeln("<info>Serve is running : </info>http://localhost:3000 ");
				return shell_exec("php -S localhost:3000 -d display_errors=1 -t web");
			}
			
		}
	}