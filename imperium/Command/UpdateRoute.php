<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\Model\Routes;
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
			 *
			 * @throws Kedavra
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
						
						$question->setAutocompleterValues(Routes::only('name'));
						
						$this->search = $helper->ask($input, $output, $question);
						
					}while(is_null($this->search) && ! def(Routes::where('name', EQUAL, $this->search)->all()));
					
					$route = Routes::by('name', $this->search);
					
					do
					{
						$question = new Question("<info>Change the method</info> <comment>[{$route->method}]</comment> : ", $route->method);
						
						$question->setAutocompleterValues(collect(METHOD_SUPPORTED)->for('strtolower')->all());
						
						$method = strtoupper($helper->ask($input, $output, $question));
						
						$this->entry->put('method', $method);
						
					}while(is_null($method));
					
					do
					{
						$question = new Question("<info>Change the name</info> <comment>[{$route->name}]</comment> : ", $route->name);
						
						$name = $helper->ask($input, $output, $question);
						
						$this->entry->put('name', $name);
						
					}while(def(Routes::where('name', EQUAL, $name)->all()));
					
					do
					{
						$question = new Question("<info>Change the url</info> <comment>[{$route->url}]</comment> : ", $route->url);
						
						$url = $helper->ask($input, $output, $question);
						
						$this->entry->put('url', $url);
						
					}while(def(Routes::where('url', EQUAL, $url)->all()));
					
					do
					{
						$question = new Question("<info>Change the controller</info> <comment>[{$route->controller}]</comment> : ", $route->controller);
						
						$question->setAutocompleterValues(controllers());
						
						$controller = $helper->ask($input, $output, $question);
						
						$this->entry->put('controller', $controller);
						
					}while(is_null($controller));
					
					do
					{
						$question = new Question("<info>Change the controller action</info> <comment>[{$route->action}]</comment> : ", $route->action);
						
						$x = "Shaolin\Controllers\\{$this->entry->get('controller')}";
						
						if(class_exists($x))
							$question->setAutocompleterValues(get_class_methods(new $x));
						
						$action = $helper->ask($input, $output, $question);
						
						$this->entry->put('action', $action);
						
					}while(is_null($action));
					
					$this->entry->put('id', $route->id);
					
					$this->routes->push(Routes::create($this->entry->all()));
					
					$this->entry->clear();
					
					$question = new Question("<info>Continue [Y/n] : </info>", 'Y');
					
					$continue = strtoupper($helper->ask($input, $output, $question));
					
					$continue = $continue === 'Y';
					
				}while($continue);
				
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				if($this->routes->ok())
				{
					$output->writeln("<info>All routes was updated successfully</info>");
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}