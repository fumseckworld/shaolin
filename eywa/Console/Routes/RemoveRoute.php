<?php

namespace Eywa\Console\Routes {


    use Exception;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class RemoveRoute extends Command
    {
        protected static $defaultName = "route:destroy";


        protected function configure(): void
        {
            $this->setDescription('Delete a route');
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output): int
        {
            $io = new SymfonyStyle($input, $output);

            $file = 'route';
            $sql = (new Sql(connect(SQLITE, base('routes', 'web.sqlite3'))))->from('routes');

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
                        $x = $io->askQuestion(
                            (new Question(config($file, 'route-name-to-delete')))
                            ->setAutocompleterValues($names)
                        );

                        if (is_null($x)) {
                            $x = '';
                        }
                        $not_exist = not_def($sql->where('name', EQUAL, $x)->get());
                        if ($not_exist) {
                            $io->error(sprintf(
                                config($file, 'route-name-not-exist'),
                                $x
                            ));
                        }
                    } while (not_def($x) || $not_exist);

                    $route = collect($sql->where('name', EQUAL, $x)->get())->get(0);

                    if ($sql->where('name', EQUAL, $route->name)->delete()) {
                        $io->success(
                            sprintf(
                                config($file, 'route-removed-successfully'),
                                $route->name
                            )
                        );
                    } else {
                        $io->error(
                            sprintf(
                                config($file, 'route-removed-fail'),
                                $route->name
                            )
                        );
                    }
                } while ($io->confirm(config($file, 'remove-route-again'), true));

                $io->success(config($file, 'bye'));
                return 0;
            }
            $io->error(config($file, 'no-routes-has-been-found'));
            return  1;
        }
    }
}
