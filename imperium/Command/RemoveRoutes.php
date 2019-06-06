<?php


namespace Imperium\Command {


    use Imperium\Routing\Route;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class RemoveRoutes extends Command
    {
        protected static $defaultName = 'routes:destroy';

        use Route;

        private $name;


        private function clean()
        {
           clear_terminal();
        }

        protected function configure()
        {
            $this->setDescription('Remove a route');
        }
        public function names(): array
        {
            $data = collection();
            foreach ($this->routes()->query()->mode(SELECT)->from('routes')->only('name')->get() as $x)
                $data->add($x->name);

            return $data->collection();
        }

        public function interact(InputInterface $input, OutputInterface $output)
        {
            if (def($this->names()))
            {
                $helper = $this->getHelper('question');

                do {
                    $this->clean();
                    $question = new Question("<info>Please enter the route name : </info>");
                    $question->setAutocompleterValues($this->names());
                    $this->name = $helper->ask($input, $output, $question);

                }while (is_null($this->name));
                while (not_def($this->name($this->name)))
                {

                    do {
                        $this->clean();
                        $question = new Question("<info>Please enter the route name : </info>");
                        $question->setAutocompleterValues($this->names());
                        $this->name = $helper->ask($input, $output, $question);


                    }while (is_null($this->name));
                }

            }

        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $this->clean();
            if (def($this->names()))
            {
                if ($this->routes()->query()->from('routes')->mode(DELETE)->where('name',EQUAL,$this->name)->delete())
                    $output->write("<info>The route has been deleted successfully</info>\n");
                else
                    $output->write("<error>The route deletion has failed</error>\n");
            }else{
                $output->write("<error>We have not found routes</error>\n");
            }
        }


        private function name(string $name): array
        {
            return $this->routes()->by('name',$name);
        }
    }
}