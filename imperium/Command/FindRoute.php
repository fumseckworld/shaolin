<?php


namespace Imperium\Command {


    use Exception;
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


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int|void
         * @throws Exception
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $helper = $this->getHelper('question');
            if (app()->table_exist(Router::ROUTES))
            {
                do {

                    do {
                        $this->clean();
                        $question = new Question("<info>Please enter the route name : </info>");

                        $this->name = $helper->ask($input, $output, $question);
                    }while (is_null($this->name));

                    $routes = app()->model()->from(Router::ROUTES)->search($this->name);

                    routes($output,$routes);
                    $question = new Question("<info>Continue searching ? [Y/n] : </info>",'Y');
                    $continue = strtoupper($helper->ask($input, $output, $question));
                    $continue = $continue === 'Y';

                }while ($continue);

            }else{
                $output->write("<error>The table routes was not found</error>\n");
                return 1;
            }
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $output->write("<info>Bye</info>\n");
        }

    }
}