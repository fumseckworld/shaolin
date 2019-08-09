<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Exception\Kedavra;
		use Imperium\Model\Routes;
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
			 * @throws Kedavra
			 * @return int|null
			 *
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				routes($output, Routes::all());
				
				return 0;
			}
			
		}
	}