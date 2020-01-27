<?php

namespace Eywa\Console\Routes {

    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Admin;
    use Eywa\Http\Routing\Task;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class UpdateRoute extends Command
    {

        protected static $defaultName = "route:update";

        /**
         *
         * All routes
         *
         */
        private Collect $routes;

        /**
         *
         * The result asked
         *
         */
        private Collect $entry;

        /**
         *
         * The value to find
         *
         */
        private string $search;

        /**
         *
         * The db choose
         *
         */
        private string $choose;



        protected function configure()
        {
            $this->setDescription('Update a route');
        }

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws Kedavra
         */
        private function name(bool $web = true, bool $admin = false)
        {
            $x = \collect();

                foreach (Web::all() as $v)
                    $x->push($v->name);
                return $x->all();
        }

        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         *
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

            $this->entry = collect();

            $this->routes = collect();
            do
            {
                clear_terminal();

                $question = new Question("<info>Route for admin, web or task ?</info> : ");

                $question->setAutocompleterValues(['admin', 'web', 'task']);

                $this->choose = $helper->ask($input, $output, $question);

            } while (is_null($this->choose) || not_in(['admin', 'web', 'task'], $this->choose));


        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         *
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            do {

                $this->ask($output,$input);

                $helper = $this->getHelper('question');
                $question = new Question("<info>Continue [Y/n] : </info>", 'Y');

                $continue = strtoupper($helper->ask($input, $output, $question)) === 'Y';

            } while ($continue);

            if ($this->routes->ok())
            {
                $output->writeln("<info>All routes was updated successfully</info>");

                return 0;
            }
            $output->writeln("<error>Fail to update routes</error>");
            return 1;
        }

        /**
         * @param OutputInterface $output
         * @param InputInterface $input
         * @return int
         * @throws Kedavra
         */
        private function ask(OutputInterface $output,InputInterface $input)
        {


            $helper = $this->getHelper('question');
            if ($this->choose == 'web' && not_def(Web::all()) || $this->choose == 'admin' && not_def(Admin::all()) || $this->choose === 'task' && not_def(Task::all())) {
                clear_terminal();

                $output->writeln("<error>The table is empty</error>");

                return 1;
            }

            do
            {
                clear_terminal();

                $question = new Question("<info>Please enter the route name : </info>");

                $question->setAutocompleterValues($this->name());

                $this->search = $helper->ask($input, $output, $question);

            } while (is_null($this->search) && not_def(Web::where('name', EQUAL, $this->search)->execute()));

            $route = Web::by('name', $this->search);

            foreach ($route as $value)
            {


                do
                {
                    $question = new Question("<info>Change the method</info> <comment>[{$value->method}]</comment> : ", $value->method);

                    $question->setAutocompleterValues(collect(METHOD_SUPPORTED)->for('strtolower')->all());

                    $method = strtoupper($helper->ask($input, $output, $question));

                    $this->entry->put('method', $method);

                } while (is_null($method));

                do
                {
                    $question = new Question("<info>Change the name</info> <comment>[{$value->name}]</comment> : ", $value->name);

                    $name = $helper->ask($input, $output, $question);

                    $this->entry->put('name', $name);

                } while (def(Web::where('name', EQUAL, $name)->execute()) && different($value->name, $name));


                do
                {
                    $question = new Question("<info>Change the url</info> <comment>[{$value->url}]</comment> : ", $value->url);

                    $url = $helper->ask($input, $output, $question);

                    $this->entry->put('url', $url);

                } while (def(Web::where('url', EQUAL, $url)->execute()) && different($value->url, $url));

                do
                {
                    $question = new Question("<info>Change the controller</info> <comment>[{$value->controller}]</comment> : ", $value->controller);
                    $question->setAutocompleterValues(controllers());
                    $controller = $helper->ask($input, $output, $question);

                    $this->entry->put('controller', $controller);

                } while (is_null($controller));

                do
                {
                    $question = new Question("<info>Change the action</info> <comment>[{$value->action}]</comment> : ", $value->action);
                    $x = "App\Controllers\\{$this->entry->get('controller')}";

                    if (class_exists($x))
                        $question->setAutocompleterValues(get_class_methods(new $x));

                    $action = $helper->ask($input, $output, $question);

                    $this->entry->put('action', $action);

                } while (is_null($action));

                $this->entry->put('id', $value->id);
                $this->save();
            }
        }

        /**
         * @throws Kedavra
         */
        public function save()
        {

            switch ($this->choose)
            {
                case 'admin':
                    $this->routes->push(Admin::update($this->entry->get('id'), $this->entry->all()));
                break;
                case 'task':
                    $this->routes->push(Task::update($this->entry->get('id'), $this->entry->all()));
                break;
                default:
                    $this->routes->push(Web::update($this->entry->get('id'), $this->entry->all()));
                break;
            }

            $this->entry->clear();
        }
    }
}