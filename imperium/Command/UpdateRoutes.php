<?php


namespace Imperium\Command {


    use Imperium\File\File;
    use Imperium\Routing\Router;
    use Sinergi\BrowserDetector\Os;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class UpdateRoutes extends Command
    {
        protected static $defaultName = 'routes:update';

        private $name;
        private $url;
        private $controller;
        private $action;
        private $method;

        /**
         * @var array
         */
        private $routes = [];
        private $id;

        private function clean()
        {
           clear_terminal();
        }

        protected function configure()
        {
          $this->setDescription('Update the routes');
        }

        private function controllers(): array
        {
            $dir = core_path(collection(config('app','dir'))->get('app')) . DIRECTORY_SEPARATOR . collection(config('app','dir'))->get('controller');

            $controllers  = collection(File::search("$dir" .DIRECTORY_SEPARATOR. '*.php'));

            $data = collection();

            if ($controllers)
            {
                foreach ($controllers as $controller)
                    $data->add(collection(explode('.',collection(explode(DIRECTORY_SEPARATOR,$controller))->last()))->begin());
            }
            return $data->collection();

        }
        private function methods():array
        {
            return collection(Router::METHOD_SUPPORTED)->each('strtolower')->collection();
        }

        private function names(): array
        {
            $data = collection();
            foreach (app()->model()->query()->mode(SELECT)->from('routes')->only('name')->get() as $x)
                $data->add($x->name);

            return $data->collection();
        }

        public function interact(InputInterface $input, OutputInterface $output)
        {


            $helper = $this->getHelper('question');

            do{

                do {
                    $this->clean();
                    $question = new Question("<info>Please enter the route name : </info>",'__________');
                    $question->setAutocompleterValues($this->names());
                    $this->name = $helper->ask($input, $output, $question);

                    while (not_def($this->get($this->name)))
                    {
                        $this->clean();
                        $question = new Question("<info>Please enter the route name : </info>",'__________');
                        $question->setAutocompleterValues($this->names());
                        $this->name = $helper->ask($input, $output, $question);
                    }



                }while (is_null($this->name));

                $this->clean();
                $this->print($output);
                foreach ($this->get($this->name) as $route)
                {
                    $this->id = $route->id;
                    do {
                        $question = new Question("<info>Change the method</info> <comment>[$route->method]</comment> : ",$route->method);
                        $question->setAutocompleterValues($this->methods());
                        $this->method = strtoupper($helper->ask($input, $output, $question));

                        while (not_in(Router::METHOD_SUPPORTED,$this->method))
                        {
                            $verbs = collection(Router::METHOD_SUPPORTED)->each('strtolower')->join(', ');
                            $output->write("<error>The method must be are $verbs  </error>\n");
                            $question = new Question("<info>Change the method</info> <comment>[$route->method]</comment> : ",$route->method);

                            $question->setAutocompleterValues($this->methods());
                            $this->method = strtoupper($helper->ask($input, $output, $question));
                        }
                    }while (is_null($this->method));

                    do {
                        $question = new Question("<info>Change the name</info> <comment>[$route->name]</comment> : ",$route->name);

                        $this->name = $helper->ask($input, $output, $question);

                        while (def($this->get($this->name)) && different($this->name,$route->name))
                        {
                            $output->write("<error>The route name already exist</error>\n");
                            $question = new Question("<info>Change the name</info> <comment>[$route->name]</comment> : ",$route->name);
                            $this->name = $helper->ask($input, $output, $question);
                        }
                    }while (is_null($this->name));

                    do {

                        $question = new Question("<info>Change the url</info> <comment>[$route->url]</comment> : ",$route->url);

                        $this->url = $helper->ask($input, $output, $question);

                        while (def(app()->model()->from('routes')->by('url',$this->url))&& different($this->url,$route->url))
                        {

                            $output->write("<error>The url already exist</error>\n");
                            $question = new Question("<info>Change the url</info> <comment>[$route->url]</comment> : ",$route->url);
                            $this->url = $helper->ask($input, $output, $question);
                        }
                    }while (is_null($this->url));


                    do {
                        $question = new Question("<info>Change the controller</info> <comment>[$route->controller]</comment> : ",$route->controller);
                        $question->setAutocompleterValues($this->controllers());
                        $this->controller = $helper->ask($input, $output, $question);
                    }while (is_null($this->controller));

                    do {

                        $question = new Question("<info>Change the action</info> <comment>[$route->action]</comment> : ",$route->action);
                        $this->action = $helper->ask($input, $output, $question);
                    }while (is_null($this->action));
                }

                $this->routes[] = ['id' => $this->id,'method' => $this->method,'name' => $this->name,'url'=> $this->url,'controller'=> $this->controller,'action'=> $this->action];

                $question = new Question("<info>Update another route [Y/n] : </info>",'Y');
                $continue = strtoupper($helper->ask($input, $output, $question));
                $continue = $continue === 'Y';
            }while($continue);
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            $data = collection();
            foreach ($this->routes as $route)
            {
                $data->add(app()->model()->from(Router::ROUTES)->update_record($route['id'],$route));
            }


            if ($data->not_exist(false))
                $output->write("<info>All routes has been successfully updated</info>\n");
            else
                $output->write("<error>The routes update has failed</error>\n");

        }

        private function print(OutputInterface $output)
        {
            $this->clean();
            routes($output, $this->get($this->name));
        }
        private function get(string $name): array
        {
            return app()->model()->from(Router::ROUTES)->by('name',$name);
        }
    }
}
