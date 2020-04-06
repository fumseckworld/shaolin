<?php

namespace Eywa\Console\Routes {

    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CleanRoutes extends Command
    {
        protected static $defaultName = "route:clean";

        protected function configure(): void
        {
            $this->setDescription('Re create the routes table');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output): int
        {
            $io = new SymfonyStyle($input, $output);

            if (file_exists(base('routes', 'web.sqlite3'))) {
                if (unlink(base('routes', 'web.sqlite3'))) {
                    $io->success(config('route', 'base-removed-successfully'));
                    if (connect(SQLITE, base('routes', 'web.sqlite3'))->connected()) {
                        if (
                            connect(SQLITE, base('routes', 'web.sqlite3'))
                            ->set('CREATE TABLE IF NOT EXISTS routes(
                                id INTEGER PRIMARY KEY AUTOINCREMENT,
                                method TEXT(10) NOT NULL, 
                                name TEXT(255) NOT NULL UNIQUE,
                                url TEXT(255) NOT NULL UNIQUE, 
                                controller TEXT(255) NOT NULL,
                                directory TEXT(255) NOT NULL,
                                action TEXT(255) NOT NULL,
                                created_at DATETIME NOT NULL ,
                                updated_at DATETIME NOT NULL )')->execute()
                        ) {
                            $io->success(config('route', 'base-created-successfully'));
                            return 0;
                        } else {
                            $io->error(config('route', 'base-created-failed'));

                            return 1;
                        }
                    } else {
                        $io->error(config('route', 'base-removed-fail'));
                        return 1;
                    }
                }
            }
            $io->error(config('route', 'base-not-exist'));
            return 1;
        }
    }
}
