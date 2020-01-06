<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Connexion\Connect;
        use Eywa\Database\Table\Table;
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
				
				$tables = collect($tables)->merge(app()->tables(),$hidden)->all();
				
				foreach($tables as $table)
				{
					is_false((new Table(ioc(Connect::class)->get()))->from($table)->drop(), true, "Failed to remove the $table table");
				}
				$output->write("<info>The $base database was cleaned successfully\n</info>");
				
				return 0;
			}
			
		}
	}