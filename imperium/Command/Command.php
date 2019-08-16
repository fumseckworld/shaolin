<?php
	
	namespace Imperium\Command
	{
		
		use Exception;
		use Imperium\Exception\Kedavra;
		use Symfony\Component\Console\Application;
		
		class Command
		{
			
			/**
			 * @var Application
			 */
			private $command;
			
			/**
			 *
			 * Command constructor.
			 *
			 * @param  string  $name
			 * @param  string  $version
			 *
			 * @throws Kedavra
			 */
			public function __construct(string $name = "UNKNOWN", string $version = 'UNKNOWN')
			{
				
				clear_terminal();
				
				$this->command = new Application($name, $version);
				
			}
			
			/**
			 *
			 * Execute the command
			 *
			 * @throws Exception
			 *
			 * @return int
			 *
			 */
			public function run() : int
			{
				
				$commands = [ new GenerateModel(), new CleanDatabase(), new Dkim(),new DumpDatabase(), new GenerateController(),new GenerateMigrations(),new MigrateDatabase(), new RollbackDatabase(),new SeedDatabase(), new AddRoute(), new ListRoute(), new RemoveRoute(), new FindRoute(), new UpdateRoute() , new Serve()];
				
				$this->add($commands)->add(commands());
				
				return $this->command->run();
			}
			
			private function add(array $commands)
			{
		
				foreach($commands as $command)
					$this->command->add($command);
				
				return $this;
			}
			
		}
	}
