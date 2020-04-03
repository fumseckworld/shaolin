<?php

namespace Eywa\Console\Database {

    use Exception;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UninstallDatabase extends Command
    {
        protected static $defaultName = 'db:uninstall';

        private string $pass = '';

        private string $mysql_root_password_question = 'What it\'s the password for the MySQL root user ?';

        private string $pgsql_root_password = 'What it\'s the password for the postgreSQL postgres user ?';

        private string $prod_user_remove_success = 'The production user has been removed successfully';

        private string $dev_user_remove_success = 'The development user has been removed successfully';

        private string $test_user_remove_success = 'The test user has been removed successfully';

        private string $prod_database_remove_success = 'The production database has been removed successfully';

        private string $dev_database_remove_success = 'The development database has been removed successfully';

        private string $test_database_remove_success = 'The test database has been removed successfully';

        private string $prod_user_remove_fail = 'The deletion of the production user\'s has failed';

        private string $dev_user_remove_fail = 'The deletion of the development user\'s has failed';

        private string $test_user_remove_fail = 'The deletion of the tests user\'s has failed';

        private string $prod_database_remove_fail = 'The deletion of the production database has failed';

        private string $dev_database_remove_fail = 'The deletion of the development database has failed';

        private string $test_database_remove_fail = 'The deletion of the test database has failed';

        private string $routing_remove_fail = 'The deletion of the routing database has failed';

        private string $routing_remove_success = 'The routing database has been deleted successfully';


        protected function configure(): void
        {
            $this->setDescription('Rollback the db:install command');
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

            $confirm = $io->confirm(sprintf('Are you sure to remove all databases ?'), false);

            if ($confirm) {
                return $this->remove(
                    strval(
                        env(
                            'DEVELOP_DB_DRIVER',
                            'mysql'
                        )
                    ),
                    strval(
                        env(
                            'DB_DRIVER',
                            'mysql'
                        )
                    ),
                    strval(
                        env(
                            'TESTS_DB_DRIVER',
                            'mysql'
                        )
                    ),
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
        private function remove(string $dev, string $prod, string $test, SymfonyStyle $io): int
        {
            switch ($dev) {
                case MYSQL:
                    do {
                        $this->pass  = $io->askQuestion(
                            (new Question($this->mysql_root_password_question, 'root'))
                                ->setHidden(true)
                        );
                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeDatabase(strval(env('DEVELOP_DB_NAME', 'ikran')))
                    ) {
                        $io->success($this->dev_database_remove_success);
                    } else {
                        $io->error($this->dev_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeUser(strval(env('DEVELOP_DB_USERNAME', 'ikran')))
                    ) {
                        $io->success($this->dev_user_remove_success);
                    } else {
                        $io->error($this->dev_user_remove_fail);
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
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeDatabase(strval(env('DEVELOP_DB_NAME', 'ikran')))
                    ) {
                        $io->success($this->dev_database_remove_success);
                    } else {
                        $io->error($this->dev_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeUser(strval(env('DEVELOP_DB_USERNAME', 'ikran')))
                    ) {
                        $io->success($this->dev_user_remove_success);
                    } else {
                        $io->error($this->dev_user_remove_fail);
                        return 1;
                    }


                    break;
                default:
                    if (
                        unlink(
                            strval(
                                env(
                                    'DEVELOP_DB_NAME',
                                    'ikran.sqlite3'
                                )
                            )
                        )
                    ) {
                        $io->success($this->dev_database_remove_success);
                    } else {
                        $io->error($this->dev_database_remove_fail);
                        return 1;
                    }
                    break;
            }

            switch ($prod) {
                case MYSQL:
                    if (!connect(MYSQL, '', 'root', $this->pass)->connected()) {
                        do {
                            $this->pass  =  $io->askQuestion(
                                (new Question($this->mysql_root_password_question, 'root'))
                                ->setHidden(true)
                            );
                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());
                    }


                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeDatabase(strval(env('DB_NAME', 'eywa')))
                    ) {
                        $io->success($this->prod_database_remove_success);
                    } else {
                        $io->error($this->prod_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeUser(strval(env('DB_USERNAME', 'eywa')))
                    ) {
                        $io->success($this->prod_user_remove_success);
                    } else {
                        $io->error($this->prod_user_remove_fail);
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
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeDatabase(strval(env('DB_NAME', 'eywa')))
                    ) {
                        $io->success($this->prod_database_remove_success);
                    } else {
                        $io->error($this->prod_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeUser(strval(env('DB_USERNAME', 'eywa')))
                    ) {
                        $io->success($this->prod_user_remove_success);
                    } else {
                        $io->error($this->prod_user_remove_fail);
                        return 1;
                    }

                    break;
                default:
                    if (
                        unlink(
                            strval(
                                env(
                                    'DEVELOP_DB_NAME',
                                    'ikran.sqlite3'
                                )
                            )
                        )
                    ) {
                        $io->success($this->prod_database_remove_success);
                    } else {
                        $io->error($this->prod_database_remove_fail);
                        return 1;
                    }
                    break;
            }

            switch ($test) {
                case MYSQL:
                    if (!connect(MYSQL, '', 'root', $this->pass)->connected()) {
                        do {
                            $this->pass  =  $io->askQuestion(
                                (new Question($this->mysql_root_password_question, 'root'))
                                ->setHidden(true)
                            );
                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());
                    }


                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeDatabase(strval(env('TESTS_DB_NAME', 'vortex')))
                    ) {
                        $io->success($this->test_database_remove_success);
                    } else {
                        $io->error($this->test_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            MYSQL,
                            '',
                            'root',
                            $this->pass
                        )->removeUser(strval(env('TESTS_DB_USERNAME', 'vortex')))
                    ) {
                        $io->success($this->test_user_remove_success);
                    } else {
                        $io->error($this->test_user_remove_fail);
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
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeDatabase(strval(env('TESTS_DB_NAME', 'vortex')))
                    ) {
                        $io->success($this->test_database_remove_success);
                    } else {
                        $io->error($this->test_database_remove_fail);
                        return 1;
                    }

                    if (
                        connect(
                            POSTGRESQL,
                            '',
                            'postgres',
                            $this->pass
                        )->removeUser(strval(env('TESTS_DB_USERNAME', 'vortex')))
                    ) {
                        $io->success($this->test_user_remove_success);
                    } else {
                        $io->error($this->test_user_remove_fail);
                        return 1;
                    }

                    break;
                default:
                    if (
                        unlink(
                            strval(
                                env(
                                    'TESTS_DB_NAME',
                                    'vortex.sqlite3'
                                )
                            )
                        )
                    ) {
                        $io->success($this->test_database_remove_success);
                    } else {
                        $io->error($this->test_database_remove_fail);
                        return 1;
                    }
                    break;
            }

            if (is_dir(base('routes'))) {
                if (unlink(base('routes', 'web.sqlite3'))) {
                    $io->success($this->routing_remove_success);
                } else {
                    $io->error($this->routing_remove_fail);
                    return 1;
                }
            }
            $io->success('Congratulations all databases are now deleted successfully');
            return 0;
        }
    }
}
