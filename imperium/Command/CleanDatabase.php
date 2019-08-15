<?php
	
	namespace Imperium\Command
	{
		
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class CleanDatabase extends Command
		{
			
			protected static $defaultName = 'db:clean';
			
			protected function configure()
			{
				
				$base = db('base');
				$this->setDescription("Clean the $base database");
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				$base = db('base');
				
				$tables = [];
				
				$hidden = db('hidden_tables');
				
				merge($tables, app()->tables(), $hidden);
				
				foreach($tables as $table)
				{
					is_false(app()->table()->drop($table), true, "Failed to remove the $table table");
				}
				$output->write("<info>The $base database was cleaned successfully\n</info>");
				
				return 0;
			}
			
		}
	}