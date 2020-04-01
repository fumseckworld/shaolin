<?php

namespace Eywa\Console\Routes {

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
        protected function configure(): void
        {
            $this->setDescription('Update a route');
        }

        /**
         * @return array<string>
         * @throws Kedavra
         */
        public function name(): array
        {
            $names = collect();

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
            $entry = collect();

            if (def($this->sql->get())) {
                do {
                    $this->sql = (new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes'));
                    do {
                        $search = $io->askQuestion(
                            (new Question('What is the name of the route to update ?', 'root'))
                            ->setAutocompleterValues($this->name())
                        );
                    } while (not_def($this->sql->where('name', EQUAL, $search)->get()));


                    $route = collect($this->sql->where('name', EQUAL, $search)->get())->get(0);


                    do {
                        $entry->put('name', $io->askQuestion((new Question('Change the route name ?', $route->name))));
                    } while (
                        not_def($entry->get('name'))
                        || def($this->sql->where('name', EQUAL, $entry->get('name'))->get())
                        && $entry->get('name') !== $route->name
                    );

                    do {
                        $entry->put('method', strtoupper(
                            $io->askQuestion(
                                (new Question('Change the route method ?', $route->method))
                            )
                        ));
                    } while (not_def($entry->get('method')) || not_in(METHOD_SUPPORTED, $entry->get('method')));

                    do {
                        $entry->put('url', $io->askQuestion((new Question('Change the route url ?', $route->url))));
                    } while (
                        not_def($entry->get('url'))
                        || def($this->sql->where('url', EQUAL, $entry->get('url'))->get())
                        && $entry->get('url') !== $route->url
                    );

                    do {
                        $entry->put('directory', $io->askQuestion(
                            (new Question('Change the route namespace ?', $route->directory))
                            ->setAutocompleterValues(controllers_directory())
                        ));
                    } while (not_def($entry->get('directory')));
                    do {
                        $entry->put(
                            'controller',
                            $io->askQuestion(
                                (new Question(sprintf('Change the route controller ?'), $route->controller))
                                ->setAutocompleterValues(
                                    controllers($entry->get('directory'))
                                )
                            )
                        );
                    } while (is_null($entry->get('controller')));

                    do {
                        if ($entry->get('directory') !== 'Controllers') {
                            $class = '\App\Controllers\\' . $entry->get('directory') . '\\' . $entry->get('controller');
                        } else {
                            $class = '\App\Controllers\\'  . $entry->get('controller');
                        }

                        if (class_exists($class)) {
                            $class = new $class();

                            $entry->put('action', $io->askQuestion(
                                (new Question('Change the route action ?', $route->action))
                                ->setAutocompleterValues(get_class_methods($class))
                            ));
                        } else {
                            $entry->put('action', $io->askQuestion(
                                (new Question('Change the route action ?', $route->action))
                            ));
                        }
                    } while (
                        not_def($entry->get('action')) &&
                        not_def($this->sql->where('action', EQUAL, $entry->get('action'))->get()) &&
                        $route->action !== $entry->get('action')
                    );


                    $entry->put('created_at', $route->created_at);
                    $entry->put('updated_at', now()->toDateTimeString());


                    if ($this->sql->update(intval($route->id), $entry->all())) {
                        $io->success('The route has been updated successfully');
                    } else {
                        $io->error('The route has not been updated');
                        return 1;
                    }

                    $entry->clear();
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
