<?php

namespace Eywa\Console\Database {

    use Exception;
    use Eywa\Database\Migration\CreateMigrationTable;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class InstallDatabase extends Command
    {
        protected static $defaultName = 'db:install';

        private string $pass = '';

        private string $mysql_root_password_question = 'What it\'s the password for the MySQL root user ?';

        private string $pgsql_root_password = 'What it\'s the password for the postgreSQL postgres user ?';

        private string $prod_user_created_fail = 'The creation of the production user has failed';

        private string $tests_user_created_fail = 'The creation of the test user has failed';

        private string $dev_user_created_fail = 'The creation of the development user has failed';

        private string $prod_base_created_fail = 'The creation of the production database has failed';

        private string $dev_base_created_fail = 'The creation of the development database has failed';

        private string $tests_base_created_fail = 'The creation of the tests database has failed';

        private string $prod_user_created_successfully = 'The production user\'s has been created successfully';

        private string $dev_user_created_successfully = 'The development user\'s has been created successfully';

        private string $tests_user_created_successfully = 'The tests user\'s has been created successfully';

        private string $prod_base_created_successfully = 'The production database has been created successfully';

        private string $dev_base_created_successfully = 'The development database has been created successfully';

        private string $tests_base_created_successfully = 'The tests database has been created successfully';

        private string $routing_instance_created_successfully = 'The routing database has been created successfully';

        private string $routing_instance_creation_has_fail = 'The creation of the routing database has failed';

        private string $migration_tables_created_successfully = 'All migrations tables has been created successfully';

        private string $migration_tables_created_failed = 'Creation of the migrations table has failed';

        protected function configure(): void
        {
            $this->setDescription('Create all databases and users rights');
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            if ($io->confirm('Run the databases creation ?', true)) {
                return  $this->create(
                    strval(env('DEVELOP_DB_DRIVER', 'mysql')),
                    strval(env('DB_DRIVER', 'mysql')),
                    strval(env('TESTS_DB_DRIVER', 'mysql')),
                    $io
                );
            }
            $io->warning('Nothing has been done ! Modify the .env file and try again');
            return 0;
        }


        /**
         * @param string $dev
         * @param string $prod
         * @param string $test
         * @param SymfonyStyle $io
         * @return int
         * @throws Kedavra
         */

        private function create(string $dev, string $prod, string $test, SymfonyStyle $io): int
        {
            if (!is_dir(base('routes'))) {
                mkdir(base('routes'));
            }

            switch ($dev) {
                case MYSQL:
                    do {
                        $this->pass  =  $io->askQuestion(
                            (new Question($this->mysql_root_password_question, 'root'))
                            ->setHidden(true)
                        );
                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                        ->createDatabase(strval(env('DEVELOP_DB_NAME', 'ikran')))
                    ) {
                        $io->success($this->dev_base_created_successfully);
                    } else {
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                         ->createUser(
                             strval(env('DEVELOP_DB_USERNAME', 'ikran')),
                             strval(env('DEVELOP_DB_PASSWORD', 'ikran')),
                             strval(env('DEVELOP_DB_NAME', 'ikran'))
                         )
                    ) {
                        $io->success($this->dev_user_created_successfully);
                    } else {
                        $io->error($this->dev_user_created_fail);
                        return 1;
                    }
                    break;

                case POSTGRESQL:
                    do {
                        $this->pass  =  $io->askQuestion(
                            (new Question($this->pgsql_root_password, 'postgres'))
                            ->setHidden(true)
                        );
                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected());


                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createDatabase(strval(env('DEVELOP_DB_NAME', 'ikran')))
                    ) {
                        $io->success($this->dev_base_created_successfully);
                    } else {
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createUser(
                            strval(env('DEVELOP_DB_USERNAME', 'ikran')),
                            strval(env('DEVELOP_DB_PASSWORD', 'ikran')),
                            strval(env('DEVELOP_DB_NAME', 'ikran'))
                        )
                    ) {
                        $io->success($this->dev_user_created_successfully);
                    } else {
                        $io->error($this->dev_user_created_fail);
                        return 1;
                    }


                    break;
                default:
                    if (connect(SQLITE, strval(env('DEVELOP_DB_NAME', 'ikran.sqlite3')))->connected()) {
                        $io->success($this->dev_base_created_successfully);
                    } else {
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }
                    break;
            }

            switch ($prod) {
                case MYSQL:
                    if (!connect(MYSQL, '', 'root', $this->pass)->connected()) {
                        do {
                            $this->pass  =  $io->askQuestion((new Question($this->mysql_root_password_question, 'root'))
                                            ->setHidden(true));
                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());
                    }


                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                        ->createDatabase(strval(env('DB_NAME', 'eywa')))
                    ) {
                        $io->success($this->prod_base_created_successfully);
                    } else {
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                        ->createUser(
                            strval(env('DB_USERNAME', 'eywa')),
                            strval(env('DB_PASSWORD', 'eywa')),
                            strval(env('DB_NAME', 'eywa'))
                        )
                    ) {
                        $io->success($this->prod_user_created_successfully);
                    } else {
                        $io->error($this->prod_user_created_fail);
                        return 1;
                    }
                    break;

                case POSTGRESQL:
                    if (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected()) {
                        do {
                            $this->pass = $io->askQuestion(
                                (new Question($this->pgsql_root_password, 'postgres'))
                                ->setHidden(true)
                            );
                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected());
                    }

                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createDatabase(strval(env('DB_NAME', 'eywa')))
                    ) {
                        $io->success($this->prod_base_created_successfully);
                    } else {
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createUser(
                            strval(env('DB_USERNAME', 'eywa')),
                            strval(env('DB_PASSWORD', 'eywa')),
                            strval(env('DB_NAME', 'eywa'))
                        )
                    ) {
                        $io->success($this->prod_user_created_successfully);
                    } else {
                        $io->error($this->prod_user_created_fail);
                        return 1;
                    }

                    break;
                default:
                    if (connect(SQLITE, strval(env('DB_NAME', 'eywa.sqlite3')))->connected()) {
                        $io->success($this->prod_base_created_successfully);
                    } else {
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }
                    break;
            }

            switch ($test) {
                case MYSQL:
                    if (!connect(MYSQL, '', 'root', $this->pass)->connected()) {
                        do {
                            $this->pass  =  $io->askQuestion((new Question($this->mysql_root_password_question, 'root'))
                                            ->setHidden(true));
                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());
                    }


                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                        ->createDatabase(strval(env('TESTS_DB_NAME', 'vortex')))
                    ) {
                        $io->success($this->tests_base_created_successfully);
                    } else {
                        $io->error($this->tests_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(MYSQL, '', 'root', $this->pass)
                        ->createUser(
                            strval(env('TESTS_DB_USERNAME', 'vortex')),
                            strval(env('TESTS_DB_PASSWORD', 'vortex')),
                            strval(env('TESTS_DB_NAME', 'vortex'))
                        )
                    ) {
                        $io->success($this->tests_user_created_successfully);
                    } else {
                        $io->error($this->tests_user_created_fail);
                        return 1;
                    }
                    break;

                case POSTGRESQL:
                    if (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected()) {
                        do {
                            $this->pass = $io->askQuestion(
                                (new Question($this->pgsql_root_password, 'postgres'))
                                ->setHidden(true)
                            );
                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected());
                    }

                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createDatabase(strval(env('TESTS_DB_NAME', 'vortex')))
                    ) {
                        $io->success($this->tests_base_created_successfully);
                    } else {
                        $io->error($this->tests_base_created_fail);
                        return 1;
                    }

                    if (
                        connect(POSTGRESQL, '', 'postgres', $this->pass)
                        ->createUser(
                            strval(env('TESTS_DB_USERNAME', 'vortex')),
                            strval(env('TESTS_DB_PASSWORD', 'vortex')),
                            strval(env('TESTS_DB_NAME', 'vortex'))
                        )
                    ) {
                        $io->success($this->tests_user_created_successfully);
                    } else {
                        $io->error($this->tests_user_created_fail);
                        return 1;
                    }

                    break;
                default:
                    if (connect(SQLITE, strval(env('TESTS_DB_NAME', 'vortex.sqlite3')))->connected()) {
                        $io->success($this->tests_base_created_successfully);
                    } else {
                        $io->error($this->tests_base_created_fail);
                        return 1;
                    }
                    break;
            }

            if (connect(SQLITE, base('routes', 'web.sqlite3'))->connected()) {
                $io->success($this->routing_instance_created_successfully);

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
                    $io->success('The routes table has been generated successfully');
                } else {
                    $io->error('The creation of the routes table has failed');

                    return 1;
                }
            } else {
                $io->error($this->routing_instance_creation_has_fail);
                return 1;
            }
            if ((new CreateMigrationTable('prod'))->up()) {
                $io->success('The production migrations table has been created successfully');
            }

            if ((new CreateMigrationTable('dev'))->up()) {
                $io->success('The development migrations table has been created successfully');
            }
            if ((new CreateMigrationTable('test'))->up()) {
                $io->success('The tests migrations table has been created successfully');
            }
            $io->success('Congratulations all databases are now ready');
            return 0;
        }
    }
}
