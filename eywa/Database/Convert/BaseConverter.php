<?php

namespace Eywa\Database\Convert {

    use Eywa\Collection\Collect;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class BaseConverter
    {

        /**
         **
         * The instance to ask and display messages
         *
         */
        private SymfonyStyle $io;

        /**
         *
         * The question to ask the mysql root password
         *
         */
        private string $mysql_root_password_question;

        /**
         *
         * The question to ask the postgresql postgres password
         *
         */
        private string $pgsql_postgres_password_question;

        /**
         *
         * The question to ask the source driver
         *
         */
        private string $driver_source_to_convert_question;

        /**
         *
         * The question to ask the destination driver
         *
         */
        private string $destination_driver_question;

        /**
         *
         *
         * All drivers supported
         *
         * @var array<string>
         *
         */
        private array $drivers = [ MYSQL,POSTGRESQL,SQLITE];

        /**
         *
         * All errors
         *
         */
        private Collect $errors;

        /**
         *
         * The source driver name
         *
         */
        private string $source_driver;

        /**
         *
         * The destination driver name
         *
         */
        private string $destination_driver;

        /**
         *
         * The message to choose the base name for convertion
         *
         */
        private string $enter_the_base_name_message;

        /**
         *
         * Mysql instance
         *
         */
        private Connect $mysql;

        /**
         *
         * Postgresql instance
         *
         */
        private Connect $pgsql;

        /**
         *
         * The source base name
         *
         */
        private string $source_base;

        /**
         *
         * The destination reserved base names
         *
         * @var array<string>
         *
         */
        private array $destination_reserved_base;

        /**
         *
         * The message to ask the new base name
         *
         */
        private string $the_new_base_name_message;

        /**
         *
         *
         * The message to ask the new password
         *
         */
        private string $the_new_username_password;

        /**
         *
         * The message to display the new base aready exist
         *
         */
        private string $the_new_base_name_already_exist_message;

        /**
         *
         * The base name to create
         *
         */
        private string $new_base_name;

        /**
         *
         * The new username
         *
         */
        private string $new_username;

        /**
         *
         * The new username password
         *
         */
        private string $new_password;

        /**
         *
         * The message to say the base not exist
         *
         */
        private string $source_bases_not_exist;

        /**
         *
         *
         * The message to say enter the new username
         *
         */
        private string $the_new_username_message;

        /**
         *
         * The message to explain the user not exist
         *
         */
        private string $user_not_exist;

        /**
         *
         * Message to explain the user already exist
         *
         */
        private string $user_aleready_exist;

        /**
         *
         * All username who already exist in source
         *
         * @var array<string>
         *
         */
        private array $destination_reserved_username;

        /**
         *
         * The confirmation message to convert the base
         *
         */
        private string $create_it;


        /**
         *
         * The message to explain the new database has been created
         *
         */
        private string $new_base_name_created;

        /**
         *
         * The message tyo explain the base has not been created
         *
         */
        private string $new_base_name_creation_fail;


        /**
         *
         * The message to explain the user has been created
         *
         */
        private string $new_username_creation_success;

        /**
         *
         * The message to explain the user has not been created
         *
         */
        private string $new_username_creation_fail;

        /**
         *
         * The name of the root user for the source
         *
         */
        private string $source_username;

        /**
         *
         * The password of the root user of the source
         *
         */
        private string $source_password;

        /**
         *
         * The source connexion
         *
         */
        private Connect $source;

        /**
         *
         * The dest connexion
         *
         */
        private Connect $dest;

        /**
         *
         * The source table connexion
         *
         */
        private Table $source_table;

        /**
         *
         * The dest table connexion
         *
         */
        private Table $dest_table;


        /**
         * BaseConverter constructor.
         *
         * @param SymfonyStyle $io
         * @throws Kedavra
         */
        public function __construct(SymfonyStyle $io)
        {
            $file = 'converter';
            $this->mysql_root_password_question = strval(config($file, 'mysql-root-password-question'));
            $this->driver_source_to_convert_question = strval(config($file, 'source-driver-to-convert-question'));
            $this->destination_driver_question = strval(config($file, 'destination-driver-question'));
            $this->pgsql_postgres_password_question = strval(config($file, 'pgsql-postgres-password-question'));
            $this->enter_the_base_name_message = strval(config($file, 'enter-source-base-name-message'));
            $this->the_new_base_name_message = strval(config($file, 'enter-new-base-name-message'));
            $this->the_new_username_message = strval(config($file, 'enter-new-username-message'));
            $this->source_bases_not_exist = strval(config($file, 'source-base-not-exist-message'));
            $this->the_new_username_password = strval(config($file, 'the-new-username-password'));
            $this->user_not_exist = strval(config($file, 'username-not-exist-message'));
            $this->user_aleready_exist = strval(config($file, 'username-already-exist-message'));
            $this->the_new_base_name_already_exist_message = strval(config($file, 'new-base-name-already-exist'));
            $this->create_it = strval(config($file, 'create-the-new-configuration'));
            $this->new_base_name_created = strval(config($file, 'new-base-name-created-successfully'));
            $this->new_base_name_creation_fail = strval(config($file, 'new-base-name-creation-fail'));
            $this->new_username_creation_fail = strval(config($file, 'new-username-creation-fail'));
            $this->new_username_creation_success = strval(config($file, 'new-username-creation-success'));
            $this->io = $io;
            $this->errors = collect();
        }


        /**
         *
         * Choose the source and destination driver
         *
         * @return BaseConverter
         *
         */
        public function choose(): BaseConverter
        {
            do {
                $this->source_driver = strval(
                    $this->io->askQuestion(
                        (new Question(
                            $this->driver_source_to_convert_question
                        ))->setAutocompleterValues($this->drivers)
                    )
                );
            } while (!in_array($this->source_driver, $this->drivers));

            do {
                $this->destination_driver =  $this->io->askQuestion(
                    (new Question(
                        $this->destination_driver_question
                    ))->setAutocompleterValues($this->drivers)
                );
            } while (
                !in_array(
                    $this->destination_driver,
                    $this->drivers
                ) || $this->destination_driver == $this->source_driver
            );

            return $this;
        }


        /**
         *
         * Save root users password
         *
         * @return BaseConverter
         *
         * @throws Kedavra
         *
         */
        public function password(): BaseConverter
        {
            if ($this->source_driver == MYSQL) {
                $this->source_username = 'root';
                do {
                    $root_password = strval($this->io->askQuestion(
                        (new Question($this->mysql_root_password_question))->setHidden(true)
                    ));
                    $this->mysql = connect(
                        MYSQL,
                        '',
                        'root',
                        $root_password,
                        LOCALHOST
                    );
                } while (not_def($root_password) || ! $this->mysql->connected());

                $this->source_password = $root_password;
            }

            if ($this->destination_driver == MYSQL) {
                do {
                    $root_password = strval($this->io->askQuestion(
                        (new Question($this->mysql_root_password_question))->setHidden(true)
                    ));
                    $this->mysql = connect(
                        MYSQL,
                        '',
                        'root',
                        $root_password,
                        LOCALHOST
                    );
                } while (not_def($root_password) || ! $this->mysql->connected());
            }

            if ($this->source_driver == POSTGRESQL) {
                $this->source_username = 'postgres';
                do {
                    $postgres_password = strval(
                        $this->io->askQuestion(
                            (new Question($this->pgsql_postgres_password_question))->setHidden(true)
                        )
                    );

                    $this->pgsql = connect(
                        POSTGRESQL,
                        '',
                        'postgres',
                        $postgres_password,
                        LOCALHOST
                    );
                } while (not_def($postgres_password) || ! $this->pgsql->connected());
                $this->source_password = $postgres_password;
            }

            if ($this->destination_driver == POSTGRESQL) {
                do {
                    $postgres_password = strval(
                        $this->io->askQuestion(
                            (new Question(
                                $this->pgsql_postgres_password_question
                            ))->setHidden(true)
                        )
                    );
                    $this->pgsql = connect(
                        POSTGRESQL,
                        '',
                        'postgres',
                        $postgres_password,
                        LOCALHOST
                    );
                } while (not_def($postgres_password) || ! $this->pgsql->connected());
            }

            return $this;
        }

        /**
         *
         *
         * Select the base to convert
         *
         * @return BaseConverter
         *
         * @throws Kedavra
         *
         */
        public function select(): BaseConverter
        {
            switch ($this->source_driver) {
                case MYSQL:
                    $source_bases = $this->mysql->set('SHOW DATABASES')->get(PDO::FETCH_COLUMN);
                    do {
                        $this->source_base = strval($this->io->askQuestion(
                            (new Question(
                                $this->enter_the_base_name_message
                            ))->setAutocompleterValues($source_bases)
                        ));
                        if (!in_array($this->source_base, $source_bases)) {
                            $this->io->error(sprintf($this->source_bases_not_exist, $this->source_base));
                            continue;
                        }
                    } while (not_def($this->source_base) || not_in($source_bases, $this->source_base));

                    break;

                case POSTGRESQL:
                    $source_bases = $this->pgsql->set(
                        'SELECT datname FROM pg_database;'
                    )->get(PDO::FETCH_COLUMN);
                    do {
                        $this->source_base = strval($this->io->askQuestion(
                            (new Question(
                                $this->enter_the_base_name_message
                            ))->setAutocompleterValues($source_bases)
                        ));
                        if (!in_array($this->source_base, $source_bases)) {
                            $this->io->error(sprintf($this->source_bases_not_exist, $this->source_base));
                            continue;
                        }
                    } while (not_def($this->source_base) || not_in($source_bases, $this->source_base));
                    break;
                case SQLITE:
                    do {
                        $this->source_base = strval(realpath(strval($this->io->askQuestion(
                            (new Question(
                                $this->enter_the_base_name_message
                            ))
                        ))));
                    } while (!file_exists($this->source_base));

                    $this->destination_reserved_base = [$this->source_base];

                    break;
            }

            switch ($this->destination_driver) {
                case MYSQL:
                        $this->destination_reserved_base = $this->mysql->set('SHOW DATABASES')->get(PDO::FETCH_COLUMN);
                        $this->destination_reserved_username = $this->mysql->set(
                            'SELECT User from mysql.user'
                        )->get(PDO::FETCH_COLUMN);
                    break;
                case POSTGRESQL:
                        $this->destination_reserved_base = $this->pgsql->set(
                            'SELECT datname FROM pg_database;'
                        )->get(PDO::FETCH_COLUMN);
                    $this->destination_reserved_username = $this->pgsql->set(
                        'SELECT rolname from pg_roles'
                    )->get(PDO::FETCH_COLUMN);
                    break;
                case SQLITE:
                        $this->destination_reserved_base = [$this->source_base];
                        $this->destination_reserved_username = [];
                    break;
            }
            return $this;
        }



        /**
         *
         * Create the new user and new base
         *
         * @return BaseConverter
         *
         * @throws Kedavra
         *
         */
        public function configure(): BaseConverter
        {
            if (
                in_array($this->source_driver, [MYSQL,POSTGRESQL]) ||
                in_array($this->destination_driver, [MYSQL,POSTGRESQL])
            ) {
                do {
                    $this->new_base_name = strval($this->io->ask($this->the_new_base_name_message));
                    if (in_array($this->new_base_name, $this->destination_reserved_base)) {
                        $this->io->error(sprintf($this->the_new_base_name_already_exist_message, $this->new_base_name));
                        continue;
                    }
                } while (
                    not_def($this->new_base_name)
                    ||
                    in_array($this->new_base_name, $this->destination_reserved_base)
                );

                do {
                    $this->new_username = strval($this->io->ask($this->the_new_username_message));
                    if (in_array($this->new_username, $this->destination_reserved_username)) {
                        $this->io->error(sprintf($this->user_aleready_exist, $this->new_username));
                        continue;
                    }
                } while (
                    not_def($this->new_username)
                    || in_array($this->new_username, $this->destination_reserved_username)
                );

                do {
                    $this->new_password = strval($this->io->ask($this->the_new_username_password));
                } while (not_def($this->new_password));
                if ($this->io->confirm($this->create_it, true)) {
                    switch ($this->destination_driver) {
                        case MYSQL:
                            if ($this->mysql->createDatabase($this->new_base_name)) {
                                $this->io->success(sprintf($this->new_base_name_created, $this->new_base_name));
                            } else {
                                $this->io->error(sprintf($this->new_base_name_creation_fail, $this->new_base_name));
                            }

                            if (
                                $this->mysql->createUser(
                                    $this->new_username,
                                    $this->new_password,
                                    $this->new_base_name
                                )
                            ) {
                                $this->io->success(sprintf($this->new_username_creation_success, $this->new_username));
                            } else {
                                $this->io->error(sprintf($this->new_username_creation_fail, $this->new_username));
                            }
                            break;
                        case POSTGRESQL:
                            if ($this->pgsql->createDatabase($this->new_base_name)) {
                                $this->io->success(sprintf($this->new_base_name_created, $this->new_base_name));
                            } else {
                                $this->io->error(sprintf($this->new_base_name_creation_fail, $this->new_base_name));
                            }

                            if (
                                $this->pgsql->createUser(
                                    $this->new_username,
                                    $this->new_password,
                                    $this->new_base_name
                                )
                            ) {
                                $this->io->success(sprintf($this->new_username_creation_success, $this->new_username));
                            } else {
                                $this->io->error(sprintf($this->new_username_creation_fail, $this->new_username));
                            }
                            break;
                        case SQLITE:
                            do {
                                $this->new_base_name = strval($this->io->ask($this->the_new_base_name_message));
                            } while (not_def($this->new_base_name));

                            if (connect(SQLITE, $this->new_base_name)->connected()) {
                                $this->io->success(sprintf($this->new_base_name_created, $this->new_base_name));
                            } else {
                                $this->io->error(sprintf($this->new_base_name_creation_fail, $this->new_base_name));
                            }
                            break;
                    }
                }
            }


            return $this;
        }

        /**
         *
         * Initialize the useful variables
         *
         * @return BaseConverter
         *
         * @throws Kedavra
         *
         */
        public function init(): BaseConverter
        {
            if (in_array($this->source_driver, [MYSQL,POSTGRESQL])) {
                $this->source = connect(
                    $this->source_driver,
                    $this->source_base,
                    $this->source_username,
                    $this->source_password,
                );


                $this->source_table = new Table($this->source);
            } else {
                $this->source = connect(SQLITE, $this->source_base);
                $this->source_table = new Table($this->source);
            }

            if (in_array($this->destination_driver, [MYSQL,POSTGRESQL])) {
                $this->dest = connect(
                    $this->destination_driver,
                    $this->new_base_name,
                    $this->new_username,
                    $this->new_password
                );
                $this->dest_table = new Table($this->dest);
            } else {
                $this->dest = connect(
                    SQLITE,
                    $this->new_base_name,
                );
                $this->dest_table = new Table($this->dest);
            }

            return $this;
        }

        /**
         *
         *
         * Create all tables
         *
         * @return BaseConverter
         *
         * @throws Kedavra
         *
         */
        public function create(): BaseConverter
        {
            $x = $this->source_table->show();

            $progess_table = $this->io->createProgressBar($x->sum());

            foreach ($x->all() as $table) {
                $this->dest_table
                    ->from($table)
                    ->create(
                        $this->source_table->columns()->all(),
                        $this->source_table->types()->all(),
                        $this->dest->driver()
                    );

                $records = $this->source->parse($table);
                $progress_records = $this->io->createProgressBar(collect($records)->sum());
                foreach ($records as $record) {
                    $this->dest->create($table, class_to_array($record));
                    $progress_records->advance();
                }
                $progess_table->advance();
            }
            return $this;
        }

        /**
         *
         * End of task
         *
         * @return int
         *
         */
        public function success(): int
        {
            $this->io->success('bye');
            return 0;
        }
    }
}
