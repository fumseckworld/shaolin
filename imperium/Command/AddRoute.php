<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\Routing\Route;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		use Symfony\Component\Console\Question\Question;
		
		class AddRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:add";
			
			/**
			 * @var Collect
			 *
			 */
			private $routes;
			
			/**
			 * @var Collect
			 */
			private $entry;
			
			protected function configure()
			{
				
				$this->setDescription('Create a new route');
			}
			
			/**
			 *
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 *
			 */
			public function interact(InputInterface $input, OutputInterface $output)
			{
				
				$helper = $this->getHelper('question');
				
				$this->routes = collect();
				
				$this->entry = collect();
				
				$methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();
				
				do
				{
					$this->entry->put('id', 'id');
					
					do
					{
						clear_terminal();
						
						$question = new Question("<info>Define the route method</info> : ");
						
						$question->setAutocompleterValues($methods);
						
						$method = strtoupper($helper->ask($input, $output, $question));
						
						$this->entry->put('method', $method);
						
					} while ( is_null($method) || not_in(METHOD_SUPPORTED, $method) );
					
					do
					{
						clear_terminal();
						
						$question = new Question("<info>Define the route name</info> : ");
						
						$name = $helper->ask($input, $output, $question);
						
						$this->entry->put('name', $name);
						
					} while ( is_null($name) || Route::manage()->check('name', $name) );
					
					do
					{
						clear_terminal();
						
						$question = new Question("<info>Define the route url</info> : ");
						
						$url = $helper->ask($input, $output, $question);
						
						$this->entry->put('url', $url);
						
						
					} while ( is_null($url) || Route::manage()->check('url', $url) );
					
					do
					{
						clear_terminal();
						
						$question = new Question("<info>Define the controller to call</info> : ");
						
						$question->setAutocompleterValues(controllers());
						
						$controller = $helper->ask($input, $output, $question);
						
						$this->entry->put('controller', $controller);
						
					} while ( is_null($controller) );
					
					do
					{
						
						clear_terminal();
						
						$question = new Question("<info>Define the action to call</info> : ");
						
						$x = "Shaolin\Controllers\\{$this->entry->get('controller')}";
						
						if ( class_exists($x) )
							$question->setAutocompleterValues(get_class_methods(new $x));
						
						$action = $helper->ask($input, $output, $question);
						
						$this->entry->put('action', $action);
						
						
					} while ( is_null($action) || ! Route::manage()->check('action', $action) );
					
					$this->routes->push($this->entry->all());
					
					$this->entry->clear();
					
					$question = new Question("<info>Create a new route [Y/n] : </info>", 'Y');
					
					$continue = strtoupper($helper->ask($input, $output, $question));
					
					$continue = $continue === 'Y';
					
				} while ( $continue );
				
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				if ( Route::manage()->create($this->routes->all()) )
				{
					
					$output->writeln('<bg=green;fg=white>All routes was successfully created</>');
					
					return 0;
				}
				
				$output->writeln('<bg=red;fg=white>Fail to create routes</>');
				
				return 0;
			}
			
		}
	}