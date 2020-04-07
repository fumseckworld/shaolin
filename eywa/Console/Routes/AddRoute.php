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

            $file = 'route';
            $entry = collect();

            $methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();
            $sql =  new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes');

            do {
                do {
                    $entry->put('name', $io->askQuestion(
                        (new Question(config($file, 'route-name-question')))
                    ));

                    if (not_def($entry->get('name'))) {
                        $entry->put('name', '');
                    }
                    $taken = def($sql->where('name', EQUAL, $entry->get('name'))->get());

                    if ($taken) {
                        $io->error(
                            sprintf(
                                config($file, 'route-name-already-taken'),
                                $entry->get('name')
                            )
                        );
                    }
                } while (not_def($entry->get('name')) || $taken);

                do {
                    $route = $entry->get('name');
                    $entry->put('method', strtoupper($io->askQuestion(
                        (new Question(
                            sprintf(
                                config($file, 'route-method-question'),
                                $route
                            ),
                            GET
                        ))->setAutocompleterValues($methods)
                    )));

                    if (not_in(METHOD_SUPPORTED, $entry->get('method'))) {
                        $io->error(
                            sprintf(
                                config($file, 'route-method-not-valid'),
                                $entry->get('method')
                            )
                        );
                    }
                } while (not_in(METHOD_SUPPORTED, $entry->get('method')));

                do {
                    $route = $entry->get('name');
                    $entry->put('url', $io->askQuestion(
                        (new Question(
                            sprintf(
                                config($file, 'route-url-question'),
                                $route
                            )
                        ))
                    ));
                    if (is_null($entry->get('url'))) {
                        $entry->put('url', '');
                    }
                    $taken = def($sql->where('url', EQUAL, $entry->get('url'))->get());

                    if ($taken) {
                        $io->error(
                            sprintf(
                                config($file, 'route-url-already-taken'),
                                $entry->get('url')
                            )
                        );
                    }
                } while (not_def($entry->get('url')) || $taken);

                do {
                    $entry->put('directory', ucfirst($io->askQuestion(
                        (new Question(
                            config($file, 'route-controller-directory-question'),
                            'Controllers'
                        ))
                        ->setAutocompleterValues(controllers_directory())
                    )));

                    $not_exist = not_in(controllers_directory(), $entry->get('directory'));

                    if ($not_exist) {
                        $io->error(
                            sprintf(
                                config($file, "route-controller-directory-not-exist"),
                                $entry->get('directory')
                            )
                        );
                    }
                } while (not_def($entry->get('directory')) || $not_exist);

                do {
                    $route = $entry->get('name');
                    $entry->put('controller', $io->askQuestion(
                        (new Question(
                            sprintf(
                                config($file, 'route-controller-question'),
                                $route
                            )
                        ))
                        ->setAutocompleterValues(controllers($entry->get('directory')))
                    ));
                    $not_exist = not_in(controllers($entry->get('directory')), $entry->get('controller'));

                    if ($not_exist) {
                        $io->error(
                            sprintf(
                                config($file, "route-controller-not-exist"),
                                $entry->get('controller')
                            )
                        );
                    }

                    $class = $entry->get('directory') !== 'Controllers' ?
                        sprintf(
                            "\App\Controllers\%s\%s",
                            $entry->get('directory'),
                            $entry->get('controller')
                        )
                    : sprintf(
                        '\App\Controllers\%s',
                        $entry->get('controller')
                    );

                    $exist = class_exists($class);

                    if (!$exist) {
                        $io->error(sprintf(
                            config($file, 'route-controller-not-exist'),
                            $entry->get('controller')
                        ));
                    }
                } while (not_def($entry->get('controller')) || $not_exist || !$exist);



                do {
                    $actions = get_class_methods($class);
                    $controller = $entry->get('controller');

                    $entry->put('action', $io->askQuestion(
                        (new Question(
                            sprintf(
                                config($file, 'route-action-question'),
                                $controller
                            )
                        ))->setAutocompleterValues($actions)
                    ));
                    if (not_def($entry->get('action'))) {
                        $entry->put('action', '');
                        $print = false;
                    } else {
                        $print = true;
                    }


                    if (not_in($actions, $entry->get('action')) && $print) {
                        $io->warning(sprintf(
                            config($file, 'route-action-not-exist'),
                            $entry->get('action')
                        ));
                    }

                    $taken = def($sql->where('action', EQUAL, $entry->get('action'))->get());

                    if ($taken) {
                        $io->error(sprintf(
                            config($file, 'route-action-already-taken'),
                            $entry->get('action')
                        ));
                    }
                } while (not_def($entry->get('action')) || $taken);

                $entry->put('created_at', now()->toDateTimeString())->put('updated_at', now()->toDateTimeString());

                if ($sql->create($entry->all())) {
                    $route = $entry->get('name');
                    $io->success(
                        sprintf(
                            config($file, 'route-created'),
                            $route
                        )
                    );
                } else {
                    $io->error(
                        sprintf(
                            config($file, 'route-create-fail'),
                            $route
                        )
                    );
                    return 1;
                }

                $entry->clear();
            } while (
                $io->confirm(
                    config($file, 'route-creation-again'),
                    true
                )
            );
            $io->success(config('route', 'bye'));
            return 0;
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         *
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {


            return 0;
        }
    }
}
