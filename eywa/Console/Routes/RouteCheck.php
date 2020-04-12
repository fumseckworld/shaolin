<?php

namespace Eywa\Console\Routes {

    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class RouteCheck extends Command
    {
        protected static $defaultName = "check:routes";

        protected function configure(): void
        {
            $this->setDescription('Check all routes');
        }

        /**
         *
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int
         *
         * @throws Kedavra
         *
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $urls = collect();
            $names = collect();
            foreach (
                (new Sql(
                    connect(
                        SQLITE,
                        base('routes', 'web.sqlite3')
                    )
                ))->from('routes')->get() as $route
            ) {
                $directory = $route->directory;

                if (def($directory) && !is_dir(base('app', 'Controllers', $directory))) {
                    $io->error(sprintf(config('route', 'directory-not-exist'), $directory));
                    return 1;
                }

                $controller = def($directory) ?
                    sprintf(
                        '\%s\Controllers\%s\%s',
                        strval(
                            config(
                                'app',
                                'namespace',
                                'App'
                            )
                        ),
                        $directory,
                        $route->controller
                    ) :
                    sprintf(
                        '\%s\Controllers\%s',
                        strval(
                            config(
                                'app',
                                'namespace',
                                'App'
                            )
                        ),
                        $route->controller
                    );
                if (!class_exists($controller)) {
                    $io->error(sprintf(config('route', 'controller-not-exist'), $route->controller));
                    return 1;
                }
                $action = $route->action;

                if (!method_exists($controller, $action)) {
                    $io->error(sprintf(
                        config('route', 'action-not-exist'),
                        $action,
                        $route->controller
                    ));
                    return 1;
                }

                if ($urls->exist($route->url)) {
                    $io->error(sprintf(config('route', 'url-not-unique'), $route->url));
                    return 1;
                } else {
                    $urls->push($route->url);
                }

                if ($names->exist($route->name)) {
                    $io->error(sprintf(config('route', 'name-not-unique'), $route->name));
                    return 1;
                } else {
                    $names->push($route->name);
                }
            }
            if (not_def($names->all())) {
                $io->error(config('route', 'no-routes-has-been-found'));
                return 1;
            }
            $io->success(config('route', 'can-use-the-router'));
            return 0;
        }
    }
}
