<?php


namespace Imperium\Command {


    use Imperium\Routing\Router;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class FindRoute extends Command
    {
        protected static $defaultName = 'routes:find';

        private $name;

        private function clean()
        {
           clear_terminal();
        }

        protected function configure()
        {
            $this->setDescription('Find a route');
        }


        public function interact(InputInterface $input, OutputInterface $output)
        {
            $helper = $this->getHelper('question');

            do {
                $this->clean();
                $question = new Question("<info>Please enter the route name : </info>");

                $this->name = $helper->ask($input, $output, $question);

                if (app()->table_exist(Router::ROUTES))
                {
                    $routes = app()->model()->from(Router::ROUTES)->search($this->name);

                    if (def($routes))
                    {
                        $output->write("+-----------------------+-----------------------+-------------------------------+-------------------------------+---------------------+\n");
                        $output->write("|\tMETHOD\t\t|\tNAME\t\t|\tURL\t\t\t|\tCONTROLLER\t\t|\tACTION\t\t|\n");
                        $output->write("+-----------------------+-----------------------+-------------------------------+-------------------------------+---------------------+\n");
                        foreach ($routes as $route)
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
                    }else{
                        $output->write("<error>No routes found</error>\n");
                    }
                }else{
                    $output->write("<error>The routes table was not fond</error>\n");
                }

                $question = new Question("<info>Continue searching ? [Y/n] : </info>",'Y');
                $continue = strtoupper($helper->ask($input, $output, $question));
                $continue = $continue === 'Y';
            }while (is_null($this->name) || $continue);
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $output->write("<info>Bye</info>\n");
        }

    }
}