<?php

namespace Eywa\Console\Routes {


    use Eywa\Collection\Collect;
    use Eywa\Database\Query\Sql;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class AddRoute extends Command
    {
        protected static $defaultName = "route:add";

        private Collect $routes;

        private Collect $entry;

        protected function configure():void
        {
            $this->setDescription('Create a new route');
        }

        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $this->routes = collect();

            $this->entry = collect();

            $methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();

            do {
                $sql =  new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes');
                do {
                    $this->entry->put('name', $io->askQuestion((new Question('What name should be used for represent the new route url ?'))));
                } while (not_def($this->entry->get('name')) || def($sql->where('name', EQUAL, $this->entry->get('name'))->get()));

                do {
                    $route = $this->entry->get('name');
                    $this->entry->put('method', strtoupper($io->askQuestion((new Question(sprintf('What method should be used for the new %s route ?', $route), GET))->setAutocompleterValues($methods))));
                } while (not_in(METHOD_SUPPORTED, $this->entry->get('method')));

                do {
                    $route = $this->entry->get('name');
                    $this->entry->put('url', $io->askQuestion((new Question(sprintf('What url should be used by the %s route for accessing the controller ?', $route)))));
                    if (is_null($this->entry->get('url'))) {
                        $this->entry->put('url', '');
                    }
                } while (not_def($this->entry->get('url') || def($sql->where('url', EQUAL, $this->entry->get('url'))->get())));

                do {
                    $this->entry->put('directory', ucfirst($io->askQuestion((new Question('Place the controller in a special directory ?', 'Controllers'))->setAutocompleterValues(controllers_directory()))));
                } while (is_null($this->entry->get('directory')));

                do {
                    $route = $this->entry->get('name');
                    $this->entry->put('controller', $io->askQuestion((new Question(sprintf('What controller name should be called by the %s route ?', $route)))->setAutocompleterValues(controllers($this->entry->get('directory')))));
                } while (is_null($this->entry->get('controller')));

                if ($this->entry->get('directory') !== 'Controllers') {
                    $class = '\App\Controllers\\' . $this->entry->get('directory') . '\\' .$this->entry->get('controller');
                } else {
                    $class = '\App\Controllers\\'  .$this->entry->get('controller');
                }

                do {
                    if (class_exists($class)) {
                        $class = new $class;

                        $controller = $this->entry->get('controller');
                        $route = $this->entry->get('name');

                        $this->entry->put('action', $io->askQuestion((new Question(sprintf('What action in the %s controller should be executed by the %s route ?', $controller, $route)))->setAutocompleterValues(get_class_methods($class))));
                    } else {
                        $controller = $this->entry->get('controller');
                        $route = $this->entry->get('name');

                        $this->entry->put('action', $io->askQuestion((new Question(sprintf('What action in the %s controller should be executed by the %s route ?', $controller, $route)))));
                    }
                } while (def($sql->where('action', EQUAL, $this->entry->get('action'))->get()) || not_def($this->entry->get('action')));

                $this->entry->put('created_at', now()->toDateTimeString())->put('updated_at', now()->toDateTimeString());


                if ($sql->create($this->entry->all())) {
                    $route = $this->entry->get('name');
                    $io->success(sprintf('The %s route has been created successfully', $route));
                } else {
                    $io->error(sprintf('Failed to create the %s route', $route));
                    return 1;
                }

                $this->entry->clear();
            } while ($io->confirm('Do you want continue to create new routes ? ', true));
            return 0;
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->success('Bye');
            return 0;
        }
    }
}
