<?php


namespace Imperium\Command {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class RoutesList extends Command
    {
        protected static $defaultName = 'routes:list';

        private $name;
        private $url;
        private $controller;
        private $action;

        protected function configure()
        {
            $this->setDescription('List all routes created');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            if (app()->table_exist('routes'))
            {
                $routes = app()->model()->from('routes')->all('id',ASC);

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
                    $output->write("<error>No route registered</error>\n");
                }
            }else{
                $output->write("<error>The routes table was not fond</error>\n");
            }
        }

    }
}