<?php


namespace Imperium\Command {


    use Imperium\Routing\Router;
    use Sinergi\BrowserDetector\Os;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class RemoveRoutes extends Command
    {
        protected static $defaultName = 'routes:destroy';

        private $name;
        private $id;

        private function clean()
        {
            os(true) ==  Os::WINDOWS ? system('cls') : system('clear');
        }

        protected function configure()
        {
            $this->setAliases(['D']);
            $this->setDescription('Remove a route');
        }


        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

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

                foreach ($this->get($this->name) as $route)
                    $this->id = $route->id;
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            if (app()->model()->from(Router::ROUTES)->remove($this->id))
                $output->write("<info>The route has been deleted successfully</info>\n");
            else
                $output->write("<error>The route deletion has failed</error>\n");

        }


        private function get(string $name): array
        {
            return app()->model()->from(Router::ROUTES)->by('name',$name);
        }
    }
}