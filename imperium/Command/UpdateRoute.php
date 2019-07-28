<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\Routing\Route;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		use Symfony\Component\Console\Question\Question;
		
		class UpdateRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:update";
			
			/**
			 * @var Collect
			 *
			 */
			private $routes;
			
			/**
			 * @var Collect
			 */
			private $entry;
			
			private $search;
			
			protected function configure()
			{
				
				$this->setDescription('Update a route');
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
				
				$this->entry = collect();
				
				$this->routes = collect();
				
				do
				{
					
					do
					{
						clear_terminal();
						
						$question = new Question("<info>Please enter the route name : </info>");
						
						$question->setAutocompleterValues(Route::manage()->names());
						
						$this->search = $helper->ask($input, $output, $question);
						
					} while ( is_null($this->search) && ! Route::manage()->check('name', $this->search) );
					
					$route = collect(Route::manage()->by($this->search));
					
					do
					{
						$question = new Question("<info>Change the method</info> <comment>[{$route->get('method')}]</comment> : ", $route->get('method'));
						
						$question->setAutocompleterValues(\collect(METHOD_SUPPORTED)->for('strtolower')->all());
						
						$method = strtoupper($helper->ask($input, $output, $question));
						
						$this->entry->put('method', $method);
						
					} while ( is_null($method) );
					
					do
					{
						$question = new Question("<info>Change the name</info> <comment>[{$route->get('name')}]</comment> : ", $route->get('name'));
						
						$name = $helper->ask($input, $output, $question);
						
						$this->entry->put('name', $name);
						
					} while ( ! Route::manage()->check('name', $name) );
					
					do
					{
						$question = new Question("<info>Change the url</info> <comment>[{$route->get('url')}]</comment> : ", $route->get('url'));
						
						$url = $helper->ask($input, $output, $question);
						
						$this->entry->put('url', $url);
						
					} while ( ! Route::manage()->check('url', $url) );
					
					do
					{
						$question = new Question("<info>Change the controller</info> <comment>[{$route->get('controller')}]</comment> : ", $route->get('controller'));
						
						$question->setAutocompleterValues(controllers());
						
						$controller = $helper->ask($input, $output, $question);
						
						$this->entry->put('controller', $controller);
						
					} while ( is_null($controller) );
					
					do
					{
						$question = new Question("<info>Change the controller action</info> <comment>[{$route->get('action')}]</comment> : ", $route->get('action'));
						
						$x = "Shaolin\Controllers\\{$this->entry->get('controller')}";
						
						if ( class_exists($x) )
							$question->setAutocompleterValues(get_class_methods(new $x));
						
						$action = $helper->ask($input, $output, $question);
						
						$this->entry->put('action', $action);
						
					} while ( is_null($action) );
					
					$this->entry->put('id',$route->get('id'));
					
					$this->routes->push($this->entry->all());
					
					$this->entry->clear();
					
					$question = new Question("<info>Continue [Y/n] : </info>", 'Y');
					
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
				
				$data = collect();
				
				
				foreach ( $this->routes->all() as $route )
					$data->push(Route::manage()->update(intval($route[ 'id' ]), $route));
				
				if ( $data->ok() )
				{
					$output->writeln("<info>All routes was updated successfully</info>");
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}