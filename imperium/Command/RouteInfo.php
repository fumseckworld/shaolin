<?php


namespace Imperium\Command {


    use Exception;
    use Imperium\Routing\Route;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class RouteInfo extends Command
    {
        use Route;
        protected static $defaultName = 'routes:info';

        private $search;

        private function clean()
        {
           clear_terminal();
        }

        protected function configure()
        {
            $this->setDescription('Display route info');
        }


        public function names(): array
        {
            $data = collection();
            foreach ($this->routes()->query()->from('routes')->mode(SELECT)->only('name')->get() as $x)
                $data->add($x->name);

            return $data->collection();
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

            if (def($this->names()))
            {
                do {

                    do {
                        $this->clean();
                        $question = new Question("<info>Please enter the route name : </info>");
                        $question->setAutocompleterValues($this->names());
                        $this->search = $helper->ask($input, $output, $question);
                    }while (is_null($this->search));

                    $routes = $this->routes()->where('name', EQUAL,$this->search)->get();

                    routes($output,$routes);
                    $question = new Question("<info>Continue ? [Y/n] : </info>",'Y');
                    $continue = strtoupper($helper->ask($input, $output, $question));
                    $continue = $continue === 'Y';

                }while ($continue);

            }


        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $this->clean();
            if (def($this->names()))
            {
                $output->write("<info>Bye</info>\n");
            }else {
               $output->write("<error>We have not found routes</error>\n");
            }
        }

    }
}