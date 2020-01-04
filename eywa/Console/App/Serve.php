<?php
	
	namespace Imperium\Command
	{
		
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class Serve extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "app:run";
			
			
			protected function configure()
			{
				
				$this->setDescription('Run a development server')->setAliases(['serve']);
			}
		
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				$output->writeln("<info>Server is running : </info>http://localhost:3000 ");
				return shell_exec("php -S localhost:3000 -d display_errors=1 -t web");
			}
			
		}
	}