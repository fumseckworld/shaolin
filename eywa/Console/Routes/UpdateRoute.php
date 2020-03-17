<?php

namespace Eywa\Console\Routes {

    use Eywa\Collection\Collect;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UpdateRoute extends Command
    {
        protected static $defaultName = "route:update";

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
        private string $search = '';
        /**
         * @var Sql
         */
        private Sql $sql;


        /**
         * FindRoute constructor.
         * @param string|null $name
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $name = null)
        {
            parent::__construct($name);

            $this->sql =  (new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes'));
        }
        protected function configure():void
        {
            $this->setDescription('Update a route');
        }

        /**
         * @return array<string>
         * @throws Kedavra
         */
        public function name():array
        {
            $names= collect();

            foreach ($this->sql->get() as $value) {
                $names->push($value->name);
            }

            return $names->all();
        }

        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         *
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            $this->entry = collect();

            if (def($this->sql->get())) {
                do {
                    $this->sql = (new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes'));
                    do {
                        $this->search = $io->askQuestion((new Question('What is the name of the route to update ?', 'root'))->setAutocompleterValues($this->name()));
                    } while (not_def($this->sql->where('name', EQUAL, $this->search)->get()));


                    $route = collect($this->sql->where('name', EQUAL, $this->search)->get())->get(0);


                    do {
                        $this->entry->put('name', $io->askQuestion((new Question('Change the route name ? ', $route->name))));
                    } while (not_def($this->entry->get('name')) || def($this->sql->where('name', EQUAL, $this->entry->get('name'))->get()) && $this->entry->get('name') !== $route->name);

                    do {
                        $this->entry->put('method', strtoupper($io->askQuestion((new Question('Change the route method ? ', $route->method)))));
                    } while (not_def($this->entry->get('method')) || not_in(METHOD_SUPPORTED, $this->entry->get('method')));

                    do {
                        $this->entry->put('url', $io->askQuestion((new Question('Change the route url ? ', $route->url))));
                    } while (not_def($this->entry->get('url')) || def($this->sql->where('url', EQUAL, $this->entry->get('url'))->get()) && $this->entry->get('url') !== $route->url);

                    do {
                        $this->entry->put('directory', $io->askQuestion((new Question('Change the route namespace ? ', $route->directory))->setAutocompleterValues(controllers_directory())));
                    } while (not_def($this->entry->get('directory')));
                    do {
                        $this->entry->put('controller', $io->askQuestion((new Question(sprintf('Change the route controller ? '), $route->controller))->setAutocompleterValues(controllers($this->entry->get('directory')))));
                    } while (is_null($this->entry->get('controller')));

                    do {
                        if ($this->entry->get('directory') !== 'Controllers') {
                            $class = '\App\Controllers\\' . $this->entry->get('directory') . '\\' .$this->entry->get('controller');
                        } else {
                            $class = '\App\Controllers\\'  .$this->entry->get('controller');
                        }

                        if (class_exists($class)) {
                            $class = new $class;

                            $this->entry->put('action', $io->askQuestion((new Question('Change the route action ? ', $route->action))->setAutocompleterValues(get_class_methods($class))));
                        } else {
                            $this->entry->put('action', $io->askQuestion((new Question('Change the route action ? ', $route->action))));
                        }
                    } while (not_def($this->entry->get('action')) && not_def($this->sql->where('action', EQUAL, $this->entry->get('action'))->get()) && $route->action !== $this->entry->get('action'));


                    $this->entry->put('created_at', $route->created_at);
                    $this->entry->put('updated_at', now()->toDateTimeString());


                    if ($this->sql->update(intval($route->id), $this->entry->all())) {
                        $io->success('The route has been updated successfully');
                    } else {
                        $io->error('The route has not been updated');
                        return 1;
                    }

                    $this->entry->clear();
                } while ($io->confirm('Continue to update routes ?', true));
                return 0;
            }
            $io->warning('Cannot update a route no routes has been found');
            return 0;
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            $io->success('Bye');
            return 0;
        }
    }
}
