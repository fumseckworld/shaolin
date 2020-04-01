<?php

namespace Eywa\Database\Migration {

    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use ReflectionClass;
    use ReflectionException;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Migrate
    {

        /**
         *
         *
         * @param SymfonyStyle $io
         *
         * @return int
         *
         * @throws ReflectionException
         * @throws Kedavra
         *
         */
        public function migrate(SymfonyStyle $io): int
        {
            if ($this->check('up')) {
                $io->warning('All databases are already up to date');

                return 0;
            }

            $result = collect();

            if (def(self::sql('dev')->execute())) {
                foreach (self::list('up') as $class => $date) {
                    $class = strval($class);
                    $x = new ReflectionClass($class);

                    $table = $x->getStaticPropertyValue('table');

                    $migration = self::file($class);

                    $created_at = $x->getStaticPropertyValue('created_at');

                    $up_success_message = str_replace('%s', $table, $x->getStaticPropertyValue('up_success_message'));

                    $up_error_message = str_replace('%s', $table, $x->getStaticPropertyValue('up_error_message'));

                    $up_title = str_replace('%s', $table, $x->getStaticPropertyValue('up_title'));

                    $exist = self::sql('dev')->where('version', EQUAL, $date)->exist();

                    if (!$exist) {
                        $i =   $x->newInstance('dev');

                        $io->title("$up_title (dev)");


                        $result->push($x->getMethod('up')->invoke($i));

                        if ($result->ok()) {
                            $io->success($up_success_message);
                            self::sql('dev')
                                ->create(
                                    [
                                        'version' => $created_at,
                                        'migration' => $migration,
                                        'time' => now()->toDateTimeString()
                                    ]
                                );
                        } else {
                            self::sql('dev')->where('version', EQUAL, $date)->delete();
                            $io->error($up_error_message);
                            return 1;
                        }
                    }

                    $exist = self::sql('prod')->where('version', EQUAL, $date)->exist();

                    if (!$exist) {
                        $i =   $x->newInstance('prod');

                        $io->title("$up_title (prod)");


                        $result->push($x->getMethod('up')->invoke($i));

                        if ($result->ok()) {
                            $io->success($up_success_message);
                            self::sql('prod')->create(
                                [
                                    'version' => $created_at,
                                    'migration' => $migration,
                                    'time' => now()->toDateTimeString()
                                ]
                            );
                        } else {
                            self::sql('prod')->where('version', EQUAL, $date)->delete();
                            $io->error($up_error_message);
                            return 1;
                        }
                    }
                }
            } else {
                foreach (self::list('up') as $class => $date) {
                    $class = strval($class);
                    $x = new ReflectionClass($class);

                    $table = $x->getStaticPropertyValue('table');

                    $migration = self::file($class);

                    $created_at = $x->getStaticPropertyValue('created_at');

                    $up_success_message = str_replace('%s', $table, $x->getStaticPropertyValue('up_success_message'));

                    $up_error_message = str_replace('%s', $table, $x->getStaticPropertyValue('up_error_message'));

                    $up_title = str_replace('%s', $table, $x->getStaticPropertyValue('up_title'));


                    $i =   $x->newInstance('dev');

                    $io->title("$up_title (dev)");

                    $result->push($x->getMethod('up')->invoke($i));

                    if ($result->ok()) {
                        $io->success($up_success_message);
                        self::sql('dev')->create(
                            [
                                'version' => $created_at,
                                'migration' => $migration,
                                'time' => now()->toDateTimeString()
                            ]
                        );
                    } else {
                        self::sql('dev')->where('version', EQUAL, $date)->delete();
                        $io->error($up_error_message);
                        return 1;
                    }

                    $i =   $x->newInstance('prod');

                    $io->title("$up_title (prod)");


                    $result->push($x->getMethod('up')->getClosure($i));

                    if ($result->ok()) {
                        $io->success($up_success_message);
                        self::sql('prod')->create(
                            [
                                'version' => $created_at,
                                'migration' => $migration,
                                'time' => now()->toDateTimeString()
                            ]
                        );
                    } else {
                        self::sql('prod')->where('version', EQUAL, $date)->delete();
                        $io->error($up_error_message);
                        return 1;
                    }
                }
            }

            $io->success('All databases are now up to date');
            return 0;
        }

        /**
         *
         * @param SymfonyStyle $io
         *
         * @return int
         *
         * @throws ReflectionException
         * @throws Kedavra
         *
         */
        public function rollback(SymfonyStyle $io): int
        {
            if ($this->check('down')) {
                $io->warning('Nothing to rollback');

                return 0;
            }

            $result = collect();

            if (def(self::sql('dev')->execute()) && def(self::sql('prod')->execute())) {
                foreach (self::list('down') as $class => $date) {
                    $class = strval($class);
                    $x = new ReflectionClass($class);

                    $table = $x->getStaticPropertyValue('table');

                    $down_success_message =
                        str_replace(
                            '%s',
                            $table,
                            $x->getStaticPropertyValue('down_success_message')
                        );

                    $down_error_message = str_replace(
                        '%s',
                        $table,
                        $x->getStaticPropertyValue('down_error_message')
                    );

                    $down_title = str_replace(
                        '%s',
                        $table,
                        $x->getStaticPropertyValue('down_title')
                    );

                    $exist = self::sql('dev')
                            ->where(
                                'version',
                                EQUAL,
                                $date
                            )
                            ->exist() &&
                        self::sql('prod')
                            ->where(
                                'version',
                                EQUAL,
                                $date
                            )
                            ->exist();


                    if ($exist) {
                        $i =  $x->newInstance('dev');

                        $io->title("$down_title (dev)");

                        $result->push($x->getMethod('down')->invoke($i));

                        if ($result->ok()) {
                            $io->success($down_success_message);
                            self::sql('dev')->where('version', EQUAL, $date)->delete();
                            $k =  $x->newInstance('prod');

                            $io->title("$down_title (prod)");

                            $result->push($x->getMethod('down')->invoke($k));

                            if ($result->ok()) {
                                $io->success($down_success_message);
                                self::sql('prod')->where('version', EQUAL, $date)->delete();
                                return 0;
                            } else {
                                $io->error($down_error_message);

                                return 1;
                            }
                        } else {
                            $io->error($down_error_message);
                            return 1;
                        }
                    }
                }
            }

            return 0;
        }

        /**
         *
         * Get an instance of the query builder
         *
         * @param string $env
         * @param string $table
         *
         * @return Sql
         *
         * @throws Kedavra
         */
        private static function sql(string $env, string $table = 'migrations'): Sql
        {
            return equal($env, 'dev') ? new Sql(development(), $table) : new Sql(production(), $table);
        }

        /**
         *
         * Get the class name
         *
         * @param string $class
         *
         * @return string
         *
         */
        private static function file(string $class): string
        {
            return collect(explode('\\', $class))->last();
        }

        /**
         *
         * List all migrations
         *
         * @param string $mode
         *
         * @return array
         *
         * @throws ReflectionException
         *
         */
        private static function list(string $mode = 'up'): array
        {
            $x = [];

            foreach (files(base('db', 'Migrations', '*.php')) as $k => $v) {
                $item = collect(explode(DIRECTORY_SEPARATOR, $v))->last();


                $item = collect(explode('.', $item))->first();

                $class = '\Evolution\Migrations\\' . $item;

                $x[$class] = (new ReflectionClass($class))->getStaticPropertyValue('created_at');
            }
            return $mode === 'up' ?  collect($x)->asort()->all() : collect($x)->arsort()->all();
        }

        /**
         *
         * Check if migrations or rollback command can be executed
         *
         * @param string $mode
         * @return bool
         *
         * @throws Kedavra
         * @throws ReflectionException
         */
        private function check(string $mode = 'up'): bool
        {
            if (equal($mode, 'up')) {
                $return = collect();
                foreach (self::list('up') as $class => $date) {
                    $return->push(self::sql('dev')
                            ->where(
                                'version',
                                EQUAL,
                                $date
                            )
                        ->exist() &&
                        self::sql('prod')
                            ->where(
                                'version',
                                EQUAL,
                                $date
                            )
                            ->exist());
                }
                return $return->ok();
            }
            return not_def(self::sql('dev')->get()) && not_def(self::sql('prod')->get());
        }
    }
}
