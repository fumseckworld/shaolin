<?php


namespace Imperium\Command {


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


        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

            do{

                do {
                    $this->clean();
                    $question = new Question("<info>Please enter the route name : </info>");

                    $this->name = $helper->ask($input, $output, $question);


                }while (is_null($this->name));

                while (not_def($this->get($this->name)))
                {

                    do {
                        $this->clean();
                        $question = new Question("<info>Please enter the route name : </info>");

                        $this->name = $helper->ask($input, $output, $question);


                    }while (is_null($this->name));
                }
                $this->clean();
                $this->print($output);
                foreach ($this->get($this->name) as $route)
                {
                    $this->id = $route->id;
                    do {
                        $question = new Question("<info>Change the method</info> <comment>[$route->method]</comment> : ",$route->method);

                        $this->method = strtoupper($helper->ask($input, $output, $question));

                        while (not_in([POST,GET],$this->method))
                        {
                            $output->write("<error>The method must be are get or post </error>\n");
                            $question = new Question("<info>Change the method</info> <comment>[$route->method]</comment> : ",$route->method);
                            $this->method = strtoupper($helper->ask($input, $output, $question));
                        }
                    }while (is_null($this->method));

                    do {
                        $question = new Question("<info>Change the name</info> <comment>[$route->name]</comment> : ",$route->name);

                        $this->name = $helper->ask($input, $output, $question);

                        while (def(app()->model()->from('routes')->by('name',$this->name)) && different($this->name,$route->name))
                        {
                            $output->write("<error>The route name already exist</error>\n");
                            $question = new Question("<info>Change the name</info> <comment>[$route->name]</comment> : ",$route->name);
                            $this->name = $helper->ask($input, $output, $question);
                        }
                    }while (is_null($this->name));

                    do {

                        $question = new Question("<info>Change the ulr</info> <comment>[$route->url]</comment> : ",$route->url);

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
                        $this->controller = $helper->ask($input, $output, $question);
                    }while (is_null($this->controller));

                    do {

                        $question = new Question("<info>Change the controller</info> <comment>[$route->action]</comment> : ",$route->action);
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
            $output->write("+-----------------------+-----------------------+-------------------------------+-------------------------------+---------------------+\n");
            $output->write("|\tMETHOD\t\t|\tNAME\t\t|\tURL\t\t\t|\tCONTROLLER\t\t|\tACTION\t\t|\n");
            $output->write("+-----------------------+-----------------------+-------------------------------+-------------------------------+---------------------+\n");
            foreach ($this->get($this->name) as $route)
            {
                $name = $route->name;
                $url = $route->url;
                $controller = $route->controller;
                $action = $route->action;
                $method = $route->method;


                $output->write("|\t$method\t\t");

                if (length($name) < 8)
                    $output->write("|\t$name\t\t|");
                else
                    $output->write("|\t$name\t|");

                if (length($url) < 8)
                    $output->write("\t$url\t\t\t|");
                else
                    $output->write("\t$url\t\t|");


                if (length($controller) < 8)
                    $output->write("\t$controller\t\t\t|");
                else if(length($controller)> 15)
                    $output->write("\t$controller\t|");
                else
                    $output->write("\t$controller\t\t|");

                if (length($action) < 8)
                    $output->write("\t$action\t\t|\n");
                else
                    $output->write("\t$action\t|\n");

                $output->write("+-----------------------+-----------------------+-------------------------------+-------------------------------+---------------------+\n");
            }
        }
        private function get(string $name): array
        {
            return app()->model()->from(Router::ROUTES)->by('name',$name);
        }
    }
}