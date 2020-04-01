<?php

namespace Eywa\Console\Routes {


    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class AddRoute extends Command
    {
        protected static $defaultName = "route:add";

        protected function configure(): void
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

            $entry = collect();

            $methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();

            do {
                $sql =  new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes');
                do {
                    $entry->put('name', $io->askQuestion(
                        (new Question('What name should be used for represent the new route url ?'))
                    ));
                } while (not_def($entry->get('name')) || def($sql->where('name', EQUAL, $entry->get('name'))->get()));

                do {
                    $route = $entry->get('name');
                    $entry->put('method', strtoupper($io->askQuestion(
                        (new Question(
                            sprintf('What method should be used for the new %s route ?', $route),
                            GET
                        ))->setAutocompleterValues($methods)
                    )));
                } while (not_in(METHOD_SUPPORTED, $entry->get('method')));

                do {
                    $route = $entry->get('name');
                    $entry->put('url', $io->askQuestion(
                        (new Question(
                            sprintf(
                                'What url should be used by the %s route for accessing the controller ?',
                                $route
                            )
                        ))
                    ));
                    if (is_null($entry->get('url'))) {
                        $entry->put('url', '');
                    }
                } while (not_def($entry->get('url') || def($sql->where('url', EQUAL, $entry->get('url'))->get())));

                do {
                    $entry->put('directory', ucfirst($io->askQuestion(
                        (new Question('Place the controller in a special directory ?', 'Controllers'))
                        ->setAutocompleterValues(controllers_directory())
                    )));
                } while (is_null($entry->get('directory')));

                do {
                    $route = $entry->get('name');
                    $entry->put('controller', $io->askQuestion(
                        (new Question(sprintf('What controller name should be called by the %s route ?', $route)))
                        ->setAutocompleterValues(controllers($entry->get('directory')))
                    ));
                } while (is_null($entry->get('controller')));

                if ($entry->get('directory') !== 'Controllers') {
                    $class = '\App\Controllers\\' . $entry->get('directory') . '\\' . $entry->get('controller');
                } else {
                    $class = '\App\Controllers\\'  . $entry->get('controller');
                }

                do {
                    if (class_exists($class)) {
                        $class = new $class();

                        $controller = $entry->get('controller');
                        $route = $entry->get('name');

                        $entry->put('action', $io->askQuestion(
                            (new Question(
                                sprintf(
                                    'What action in the %s controller should be executed by the %s route ?',
                                    $controller,
                                    $route
                                )
                            ))
                            ->setAutocompleterValues(get_class_methods($class))
                        ));
                    } else {
                        $controller = $entry->get('controller');
                        $route = $entry->get('name');

                        $entry->put('action', $io->askQuestion(
                            (new Question(sprintf(
                                'What action in the %s controller should be executed by the %s route ?',
                                $controller,
                                $route
                            )))
                        ));
                    }
                } while (
                    def($sql->where('action', EQUAL, $entry->get('action'))->get())
                    || not_def($entry->get('action'))
                );

                $entry->put('created_at', now()->toDateTimeString())->put('updated_at', now()->toDateTimeString());


                if ($sql->create($entry->all())) {
                    $route = $entry->get('name');
                    $io->success(sprintf('The %s route has been created successfully', $route));
                } else {
                    $io->error(sprintf('Failed to create the %s route', $route));
                    return 1;
                }

                $entry->clear();
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
