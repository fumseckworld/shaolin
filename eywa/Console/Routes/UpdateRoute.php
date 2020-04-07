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

        protected function configure(): void
        {
            $this->setDescription('Update an existing route');
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
            $sql = new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes');
            $methods = collect(METHOD_SUPPORTED)->for('strtolower')->all();

            $names = call_user_func(function () use ($sql) {
                $x = collect();
                foreach ($sql->get() as $route) {
                    $x->push($route->name);
                }
                return $x->all();
            });

            if (def($names)) {
                do {
                    do {
                        $search = $io->askQuestion(
                            (new Question(
                                config($file, 'route-name'),
                                'root'
                            ))->setAutocompleterValues($names)
                        );
                        if (not_in($names, $search)) {
                            $io->error(sprintf(
                                config($file, 'route-name-not-exist'),
                                $search
                            ));
                        }
                    } while (not_in($names, $search) || not_def($search));

                    $route = collect($sql->where('name', EQUAL, $search)->get())->get(0);
                    do {
                        $entry->put('name', $io->askQuestion(
                            (new Question(config($file, 'change-name'), $route->name))
                        ));
                        $taken =
                            def($sql->where('name', EQUAL, $entry->get('name'))->get())
                            && $entry->get('name') !== $route->name;

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
                        $entry->put('method', strtoupper($io->askQuestion(
                            (new Question(
                                config($file, 'change-method'),
                                $route->method
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
                        $entry->put('url', $io->askQuestion(
                            (new Question(
                                config($file, 'change-url'),
                                $route->url
                            ))
                        ));
                        if (is_null($entry->get('url'))) {
                            $entry->put('url', '');
                        }
                        $taken = def($sql->where('url', EQUAL, $entry->get('url'))->get())
                        && $entry->get('url') !== $route->url;

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
                                config($file, 'change-directory'),
                                $route->directory
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
                    } while (is_null($entry->get('directory')) || $not_exist);

                    do {
                        $entry->put('controller', $io->askQuestion(
                            (new Question(
                                config($file, 'change-controller'),
                                $route->controller
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
                    } while (is_null($entry->get('controller')) || $not_exist || !$exist);



                    do {
                        $actions = get_class_methods($class);

                        $entry->put('action', $io->askQuestion(
                            (new Question(
                                config($file, 'change-action'),
                                $route->action
                            ))->setAutocompleterValues($actions)
                        ));

                        if (not_in($actions, $entry->get('action'))) {
                            $io->warning(sprintf(
                                config($file, 'route-action-not-exist'),
                                $entry->get('action')
                            ));
                        }
                        $taken = def($sql->where('action', EQUAL, $entry->get('action'))->get())
                        &&  $route->action !== $entry->get('action')
                        ;

                        if ($taken) {
                            $io->error(sprintf(
                                config($file, 'route-action-already-taken'),
                                $entry->get('action')
                            ));
                        }
                    } while ($taken || not_def($entry->get('action')));

                    $entry->put('created_at', $route->created_at)->put('updated_at', now()->toDateTimeString());


                    if ($sql->update($route->id, $entry->all())) {
                        $route = $entry->get('name');
                        $io->success(
                            sprintf(
                                config($file, 'route-updated-successfully'),
                                $route
                            )
                        );
                    } else {
                        $io->error(
                            sprintf(
                                config($file, 'route-update-failed'),
                                $route
                            )
                        );
                        return 1;
                    }

                    $entry->clear();
                } while (
                    $io->confirm(
                        config($file, 'route-update-again'),
                        true
                    )
                );
                $io->success(config('route', 'bye'));
                return 0;
            }

            $io->error(config($file, 'no-routes-has-been-found'));
            return 1;
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
