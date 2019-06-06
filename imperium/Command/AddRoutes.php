<?php

namespace Imperium\Command {

    use Imperium\Collection\Collection;
    use Imperium\Routing\Route;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class AddRoutes extends Command
    {
        use Route;
        
        protected static $defaultName = 'routes:add';

        private $name;
        private $url;
        private $controller;
        private $action;
        private $method;


        /**
         * @var array
         */
        private $routes = [];

        private function clean()
        {
            clear_terminal();
        }
        protected function configure()
        {
            $this->setDescription('Create a new route');
        }

        private function controllers(): array
        {
           return controllers();

        }
        public function methods():Collection
        {
            return collection(METHOD_SUPPORTED)->each('strtolower');
        }
        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

            do{

                do {
                    $this->clean();

                    $question = new Question("<info>Set the route method : </info>");
                    $question->setAutocompleterValues($this->methods()->collection());
                    $this->method = strtoupper($helper->ask($input, $output, $question));

                    while (not_in(METHOD_SUPPORTED,$this->method))
                    {
                        $this->clean();

                        $output->write("<error>The method must be {$this->methods()->join(', ')} </error>\n");
                        $question = new Question("<info>Set the route method : </info>");
                        $question->setAutocompleterValues($this->methods()->collection());
                        $this->method = strtoupper($helper->ask($input, $output, $question));
                    }
                }while (is_null($this->method));

                do {
                    $this->clean();
                    $question = new Question("<info>Set the route name : </info>");

                    $this->name = $helper->ask($input, $output, $question);

                    while (def($this->routes()->by('name',$this->name)))
                    {
                        $this->clean();
                        $output->write("<error>The route name already exist</error>\n");
                        $question = new Question("<info>Set the route name : </info>");
                        $this->name = $helper->ask($input, $output, $question);
                    }
                }while (is_null($this->name));

                do {
                    $this->clean();
                    $question = new Question("<info>Set the route url : </info>");

                    $this->url = $helper->ask($input, $output, $question);

                    while (def($this->routes()->by('url',$this->url)))
                    {
                        $this->clean();
                        $output->write("<error>The url already exist</error>\n");
                        $question = new Question("<info>Set the route url : </info>");
                        $this->url = $helper->ask($input, $output, $question);
                    }
                }while (is_null($this->url));


                do {
                    $this->clean();
                    $question = new Question("<info>Set the controller to call : </info>");
                    $question->setAutocompleterValues($this->controllers());
                    $this->controller = $helper->ask($input, $output, $question);
                }while (is_null($this->controller));

                do {
                    $this->clean();
                    $question = new Question("<info>Set the action to call : </info>");
                    $this->action = $helper->ask($input, $output, $question);
                }while (is_null($this->action));

                $this->routes[] = ['id' => 'id','name' => $this->name,'url' => $this->url,'controller' => $this->controller,'action' => $this->action,'method' => $this->method];

                $this->clean();
                $question = new Question("<info>Create a new route [Y/n] : </info>",'Y');
                $continue = strtoupper($helper->ask($input, $output, $question));
                $continue = $continue === 'Y';
            }while($continue);


        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            $data = collection();
            foreach ($this->routes as $route)
                $data->add($this->save_route($route));

            $this->clean();
            if ($data->not_exist(false))
                $output->write("<info>All routes has been successfully created</info>\n");
            else
                $output->write("<error>The routes generation has failed</error>\n");

        }


    }
}
