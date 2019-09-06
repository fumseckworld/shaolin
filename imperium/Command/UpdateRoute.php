<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
        use Imperium\Model\Admin;
        use Imperium\Model\Web;
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
            /**
             * @var string
             */
            private $choose;

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
                do {
                    clear_terminal();

                    $question = new Question("<info>Route for admin or web ?</info> : ");

                    $question->setAutocompleterValues(['admin', 'web']);

                    $this->choose = $helper->ask($input, $output, $question);

                } while (is_null($this->choose) || not_in(['admin', 'web'], $this->choose));

				
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             *
             * @return int|null
             * @throws Kedavra
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $helper = $this->getHelper('question');

                if ($this->choose == 'web' && not_def(Web::all()) || $this->choose == 'admin' && not_def(Admin::all()))
                {
                    clear_terminal();

                    $output->writeln("<error>The table is empty</error>");

                    return 1;
                }
                do
                {
                    if ($this->choose =='web')
                    {
                        do
                        {
                            clear_terminal();

                            $question = new Question("<info>Please enter the route name : </info>");

                            $question->setAutocompleterValues(Web::only('name'));

                            $this->search = $helper->ask($input, $output, $question);

                        }while(is_null($this->search) && not_def(Web::where('name', EQUAL, $this->search)->all()));

                        $route = Web::by('name', $this->search);

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

                        }while(def(Web::where('name', EQUAL, $name)->all()) && different($route->name,$name));



                        do
                        {
                            $question = new Question("<info>Change the url</info> <comment>[{$route->url}]</comment> : ", $route->url);

                            $url = $helper->ask($input, $output, $question);

                            $this->entry->put('url', $url);

                        }while(def(Web::where('url', EQUAL, $url)->all()) && different($route->url,$url));

                        do
                        {
                            $question = new Question("<info>Change the controller</info> <comment>[{$route->controller}]</comment> : ", $route->controller);
                            $question->setAutocompleterValues(controllers());
                            $controller = $helper->ask($input, $output, $question);

                            $this->entry->put('controller', $controller);

                        }while(is_null($controller));

                        do
                        {
                            $question = new Question("<info>Change the action</info> <comment>[{$route->action}]</comment> : ", $route->action);
                            $x = "App\Controllers\\{$this->entry->get('controller')}";

                            if(class_exists($x))
                                $question->setAutocompleterValues(get_class_methods(new $x));

                            $action = $helper->ask($input, $output, $question);

                            $this->entry->put('action', $action);

                        }while(is_null($action));

                        $this->entry->put('id', $route->id);
                    }else {
                        do {
                            clear_terminal();

                            $question = new Question("<info>Please enter the route name : </info>");

                            $question->setAutocompleterValues(Web::only('name'));

                            $this->search = $helper->ask($input, $output, $question);

                        } while (is_null($this->search) && not_def(Admin::where('name', EQUAL, $this->search)->all()));

                        $route = Admin::by('name', $this->search);

                        do {
                            $question = new Question("<info>Change the method</info> <comment>[{$route->method}]</comment> : ", $route->method);

                            $question->setAutocompleterValues(collect(METHOD_SUPPORTED)->for('strtolower')->all());

                            $method = strtoupper($helper->ask($input, $output, $question));

                            $this->entry->put('method', $method);

                        } while (is_null($method));

                        do {
                            $question = new Question("<info>Change the name</info> <comment>[{$route->name}]</comment> : ", $route->name);

                            $name = $helper->ask($input, $output, $question);

                            $this->entry->put('name', $name);

                        } while (def(Admin::where('name', EQUAL, $name)->all()) && different($route->name, $name));


                        do {
                            $question = new Question("<info>Change the url</info> <comment>[{$route->url}]</comment> : ", $route->url);

                            $url = $helper->ask($input, $output, $question);

                            $this->entry->put('url', $url);

                        } while (def(Admin::where('url', EQUAL, $url)->all()) && different($route->url, $url));

                        do {
                            $question = new Question("<info>Change the controller</info> <comment>[{$route->controller}]</comment> : ", $route->controller);
                            $question->setAutocompleterValues(controllers());
                            $controller = $helper->ask($input, $output, $question);

                            $this->entry->put('controller', $controller);

                        } while (is_null($controller));

                        do {
                            $question = new Question("<info>Change the action</info> <comment>[{$route->action}]</comment> : ", $route->action);
                            $x = "App\Controllers\\{$this->entry->get('controller')}";

                            if (class_exists($x))
                                $question->setAutocompleterValues(get_class_methods(new $x));

                            $action = $helper->ask($input, $output, $question);

                            $this->entry->put('action', $action);

                        } while (is_null($action));

                        $this->entry->put('id', $route->id);
                    }

                    $this->choose == 'admin' ? $this->routes->push(Admin::update($this->entry->get('id'),$this->entry->all())) : $this->routes->push(Web::update($this->entry->get('id'),$this->entry->all()));

                    $this->entry->clear();

                    $question = new Question("<info>Continue [Y/n] : </info>", 'Y');

                    $continue = strtoupper($helper->ask($input, $output, $question));

                    $continue = $continue === 'Y';

                }while($continue);

				if($this->routes->ok())
				{
					$output->writeln("<info>All routes was updated successfully</info>");
					
					return 0;
				}
                $output->writeln("<error>Fail to update routes</error>");
				return 1;
			}
			
		}
	}