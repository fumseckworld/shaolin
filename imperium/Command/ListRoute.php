<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Routing\Route;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class ListRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:list";
			
			protected function configure()
			{
				$this->setDescription('List all routes');
			}
			
			/**
			 *
			 * List routes
			 *
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 *
			 */
			public function execute( InputInterface $input, OutputInterface $output )
			{
				Route::manage()->list($input, $output);
				
				return 0;
			}
			
		}
	}