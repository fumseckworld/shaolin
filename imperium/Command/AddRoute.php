<?php

namespace Imperium\Command {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Collection\Collect;
    use Imperium\Exception\Kedavra;
    use Imperium\Model\Task;
    use Imperium\Model\Web;
    use Imperium\Model\Admin;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class AddRoute extends \Symfony\Component\Console\Command\Command
    {
        protected static $defaultName = "route:add";

        /**
         * @var Collect
         *
         */
        private $routes;

        /**
         * @var Collect
         */
        private $entry;

        protected function configure()
        {
            $this->setDescription('Create a new route');
        }

        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $helper = $this->getHelper('question');
            $this->routes = collect();
            $this->entry = collect();
            $methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();

            do {
                $this->entry->put('id', 'id');

                do {
                    clear_terminal();

                    $question = new Question("<info>Route for admin, web or task ?</info> : ");

                    $question->setAutocompleterValues(['admin', 'web','task']);

                    $route = $helper->ask($input, $output, $question);

                    $this->entry->put('route', $route);

                } while (is_null($route) || not_in(['admin', 'web' ,'task'], $route));

                do {
                    clear_terminal();

                    $question = new Question("<info>Define the route method</info> : ");

                    $question->setAutocompleterValues($methods);

                    $method = strtoupper($helper->ask($input, $output, $question));

                    $this->entry->put('method', $method);
                } while (is_null($method) || not_in(METHOD_SUPPORTED, $method));

                if ($this->entry->get('route') == 'web')
                {
                    do {
                        clear_terminal();

                        $question = new Question("<info>Define the route name</info> : ");

                        $name = $helper->ask($input, $output, $question);

                        $this->entry->put('name', $name);

                    } while (is_null($name) || def(Web::where('name', EQUAL, $name)->all()));

                    do {
                        clear_terminal();

                        $question = new Question("<info>Define the route url</info> : ");

                        $url = $helper->ask($input, $output, $question);

                        $this->entry->put('url', $url);

                    } while (is_null($url) || def(Web::where('url', EQUAL, $url)->all()));

                    do {

                        clear_terminal();

                        $question = new Question("<info>Define the controller to call</info> : ");

                        $question->setAutocompleterValues(controllers());

                        $controller = $helper->ask($input, $output, $question);

                        $this->entry->put('controller', $controller);

                    } while (is_null($controller));

                    do {

                        clear_terminal();

                        $question = new Question("<info>Define the action to call</info> : ");

                        $x = "App\Controllers\\{$this->entry->get('controller')}";

                        if (class_exists($x))
                            $question->setAutocompleterValues(get_class_methods(new $x));

                        $action = $helper->ask($input, $output, $question);

                        $this->entry->put('action', $action);

                    } while (is_null($action) || def(Web::where('action', EQUAL, $action)->all()));

                } elseif($this->entry->get('route') == 'admin')
                {


                        do {
                            clear_terminal();

                            $question = new Question("<info>Define the route name</info> : ");

                            $name = $helper->ask($input, $output, $question);

                            $this->entry->put('name', $name);

                        } while (is_null($name) || def(Admin::where('name', EQUAL, $name)->all()));

                         do {
                            clear_terminal();

                            $question = new Question("<info>Define the route url</info> : ");

                            $url = $helper->ask($input, $output, $question);

                            $this->entry->put('url', $url);

                        } while (is_null($url) || def(Admin::where('url', EQUAL, $url)->all()));


                        do {

                            clear_terminal();

                            $question = new Question("<info>Define the controller to call</info> : ");

                            $question->setAutocompleterValues(controllers());

                            $controller = $helper->ask($input, $output, $question);

                            $this->entry->put('controller', $controller);

                        } while (is_null($controller));

                         do {

                            clear_terminal();

                            $question = new Question("<info>Define the action to call</info> : ");

                            $x = "App\Controllers\\{$this->entry->get('controller')}";

                            if (class_exists($x))
                                $question->setAutocompleterValues(get_class_methods(new $x));

                            $action = $helper->ask($input, $output, $question);

                            $this->entry->put('action', $action);

                        } while (is_null($action) || def(Admin::where('action', EQUAL, $action)->all()));

                }else
                {
                    do
                    {
                        clear_terminal();

                        $question = new Question("<info>Define the route name</info> : ");

                        $name = $helper->ask($input, $output, $question);

                        $this->entry->put('name', $name);

                    } while (is_null($name) || def(Task::where('name', EQUAL, $name)->all()));

                    do {
                        clear_terminal();

                        $question = new Question("<info>Define the route url</info> : ");

                        $url = $helper->ask($input, $output, $question);

                        $this->entry->put('url', $url);

                    } while (is_null($url) || def(Task::where('url', EQUAL, $url)->all()));


                    do {

                        clear_terminal();

                        $question = new Question("<info>Define the controller to call</info> : ");

                        $question->setAutocompleterValues(controllers());

                        $controller = $helper->ask($input, $output, $question);

                        $this->entry->put('controller', $controller);

                    } while (is_null($controller));

                    do {

                        clear_terminal();

                        $question = new Question("<info>Define the action to call</info> : ");

                        $x = "App\Controllers\\{$this->entry->get('controller')}";

                        if (class_exists($x))
                            $question->setAutocompleterValues(get_class_methods(new $x));

                        $action = $helper->ask($input, $output, $question);

                        $this->entry->put('action', $action);

                    } while (is_null($action) || def(Task::where('action', EQUAL, $action)->all()));
                }

                switch ($this->entry->get('route'))
                {
                    case 'admin':
                        $this->routes->push(Admin::create($this->entry->all()));
                    break;
                    case 'task':
                        $this->routes->push(Task::create($this->entry->all()));
                    break;
                    default:
                        $this->routes->push(Web::create($this->entry->all()));
                    break;
                }

                $this->entry->clear();

                $question = new Question("<info>Create a new route [Y/n] : </info>", 'Y');

                $continue = strtoupper($helper->ask($input, $output, $question));

                $continue = $continue === 'Y';

            } while ($continue);
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            if ($this->routes->ok())
            {
                $output->writeln('<info>All routes was successfully created</info>');
                return 0;
            }
            $output->writeln('<bg=red;fg=white>Fail to create routes</>');
            return 0;
        }

    }
}