<?php
	
	namespace Imperium\Command
	{
		
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class SeedDatabase extends Command
		{
			
			protected static $defaultName = 'db:seed';
			
			protected function configure()
			{
				
				$this->setAliases([ 'seed' ]);
				$this->setDescription('Seed the database');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				return system("./vendor/bin/phinx seed:run -e development");
			}
			
		}
	}