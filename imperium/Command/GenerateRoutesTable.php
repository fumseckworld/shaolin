<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
		use Imperium\Model\Routes;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class GenerateRoutesTable extends Command
		{
			
			protected static $defaultName = 'route:generate';
			
			protected function configure()
			{
				
				$this->setDescription('Generate the router table');
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 * @return bool|int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				Dir::create('app' .DIRECTORY_SEPARATOR . 'Routes');
				return Routes::generate();
			}
			
		}
	}
