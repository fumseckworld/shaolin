<?php


namespace Eywa\Console\Database {

    use Exception;
    use Eywa\Database\Migration\CreateMigrationTable;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class InstallDatabase extends Command
    {
        protected static $defaultName = 'db:install';
        /**
         *
         * The root password
         *
         */
        private string $pass;

        /**
         * @var string
         */
        private string $mysql_pass;
        /**
         * @var string
         */
        private string $pgsql_pass;

        protected function configure():void
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

            $dev_base_created_successfully = 'The development database has been created successfully';
            $prod_base_created_successfully = 'The production database has been created successfully';
            $dev_user_created_successfully = 'The development user has been created successfully';
            $prod_user_created_successfully = 'The production user has been created successfully';

            $dev_base_created_fail = 'The development database has been created successfully';
            $prod_base_created_fail = 'The production database has been created successfully';
            $dev_user_created_fail = 'The development user has been created successfully';
            $prod_user_created_fail = 'The production user has been created successfully';

            $mysql_root_password_question = "What it's the password for the MySQL root user ?";
            $pgsql_root_password = "What it's the password for the postgreSQL postgres user ?";

            $prod = production()->info();
            $dev = development()->info();

            if($io->confirm("Are you sure to use the following as a production database ? \n\n<fg=black;bg=yellow>$prod</>",false) && $io->confirm("Are you sure to use the following as a development database ? \n\n<fg=black;bg=yellow>$dev</>",false))
            {


                if (in_array(development()->driver(), [MYSQL, POSTGRESQL]) && in_array(production()->driver(),[MYSQL,POSTGRESQL]))
                {


                    $io->title('Creating all databases');

                    if (development()->driver() === MYSQL && production()->driver() === MYSQL)
                    {

                        do
                        {
                            $this->pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                        $dev = connect(MYSQL,'','root',$this->pass);

                        $prod = connect(MYSQL,'','root',$this->pass);


                        if ($dev->create_database(strval(strval(env('DEVELOP_DB_NAME')))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->create_database(strval(strval(env('DB_NAME')))))
                        {

                            $io->success($prod_base_created_successfully);

                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(MYSQL,'','root',$this->pass);

                        $prod = connect(MYSQL,'','root',$this->pass);

                        if ($prod->create_user(strval(strval(env('DB_USERNAME'))), strval(strval(env('DB_PASSWORD'))), strval(strval(env('DB_NAME')))))
                        {

                            $io->success($prod_user_created_successfully);

                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;
                        }
                        if ($dev->create_user(strval(strval(env('DEVELOP_DB_USERNAME'))), strval(strval(env('DEVELOP_DB_PASSWORD'))), strval(strval(env('DEVELOP_DB_NAME')))))
                        {
                            $io->success($dev_user_created_successfully);
                        } else {
                            $io->error($dev_user_created_fail);
                            return 1;
                        }

                    }

                    if (development()->driver() === MYSQL && production()->driver() === POSTGRESQL)
                    {

                        do
                        {
                            $this->mysql_pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());


                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                        $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($dev->create_database(strval(strval(env('DEVELOP_DB_NAME')))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->create_database(strval(strval(env('DB_NAME')))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($dev->create_user(strval(strval(env('DEVELOP_DB_USERNAME'))), strval(strval(env('DEVELOP_DB_PASSWORD'))), strval(strval(env('DEVELOP_DB_NAME')))))
                        {
                            $io->success($dev_user_created_successfully);
                        } else {
                            $io->error($dev_user_created_fail);
                            return 1;

                        }



                        if ($prod->create_user(strval(strval(env('DB_USERNAME'))), strval(strval(env('DB_PASSWORD'))),strval(strval(env('DB_NAME')))))
                        {
                            $io->success($prod_user_created_successfully);
                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;

                        }

                    }

                    if (development()->driver() === POSTGRESQL && production()->driver() === MYSQL)
                    {

                        do
                        {
                            $this->mysql_pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());


                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                        $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if ($dev->create_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->create_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }
                        $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if ($prod->create_user(strval(env('DB_USERNAME')), strval(env('DB_PASSWORD')), strval(env('DB_NAME'))))
                        {
                            $io->success($prod_user_created_successfully);
                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;

                        }
                        if ($dev->create_user(strval(env('DEVELOP_DB_USERNAME')), strval(env('DEVELOP_DB_PASSWORD')), strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        } else {
                            $io->error($dev_user_created_fail);
                            return 1;

                        }

                    }

                    if (development()->driver() === POSTGRESQL && production()->driver() === POSTGRESQL)
                    {

                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());


                        $dev = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($dev->create_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->create_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($prod->create_user(strval(env('DB_USERNAME')), strval(env('DB_PASSWORD')), strval(env('DB_NAME'))))
                        {
                            $io->success($prod_user_created_successfully);
                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;

                        }

                        if ($dev->create_user(strval(env('DEVELOP_DB_USERNAME')), strval(env('DEVELOP_DB_PASSWORD')), strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        } else {
                            $io->error($dev_user_created_fail);
                            return 1;

                        }
                    }

                } else {
                    if (development()->driver() ===  SQLITE && production()->driver() === MYSQL)
                    {



                        do
                        {
                            $this->mysql_pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());



                        $dev = connect(SQLITE, strval(env('DEVELOP_DB_NAME')));

                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if ($dev->connected())
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }

                        if ($prod->create_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        $prod = connect(MYSQL, '', 'root', $this->pass);
                        if ($prod->create_user(strval(env('DB_USERNAME')),strval(env('DB_PASSWORD')),strval(env('DB_NAME'))))
                        {
                            $io->success($prod_user_created_successfully);
                        }else{
                            $io->error($prod_user_created_fail);
                        }
                    }

                    if (development()->driver() ===  MYSQL && production()->driver() === SQLITE)
                    {

                        do
                        {
                            $this->mysql_pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());


                        $dev = connect(MYSQL,'','root',$this->mysql_pass);
                        $prod = connect(SQLITE, strval(env('DB_NAME')));

                        if ($prod->connected())
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        if ($dev->create_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                        $dev = connect(MYSQL,'','root',$this->pass);
                        if ($dev->create_user(strval(env('DEVELOP_DB_USERNAME')),strval(env('DEVELOP_DB_PASSWORD')),strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        }else{
                            $io->error($dev_user_created_fail);
                        }
                    }

                    if (development()->driver() ===  SQLITE && production()->driver() === POSTGRESQL)
                    {


                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());


                        $prod = connect(POSTGRESQL,'','postgres',$this->pgsql_pass,5432);

                        $dev = connect(SQLITE, strval(env('DEVELOP_DB_NAME')));

                        if ($dev->connected())
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }

                        if ($prod->create_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }
                        $prod = connect(POSTGRESQL,'','postgres',$this->pass,5432);
                        if ($prod->create_user(strval(env('DB_USERNAME')),strval(env('DB_PASSWORD')),strval(env('DB_NAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        }else{
                            $io->error($dev_user_created_fail);
                        }
                    }

                    if (development()->driver() ===  POSTGRESQL && production()->driver() === SQLITE)
                    {

                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());


                        $prod = connect(SQLITE,strval(env('DB_NAME')));
                        $dev = connect(POSTGRESQL, '','postgres',$this->pgsql_pass,5432);

                        if ($prod->connected())
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        if ($dev->create_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                        $dev = connect(POSTGRESQL, '','postgres',$this->pass,5432);
                        if ($dev->create_user(strval(env('DEVELOP_DB_USERNAME')),strval(env('DEVELOP_DB_PASSWORD')),strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        }else{
                            $io->error($dev_user_created_fail);
                        }

                    }
                    if (development()->driver() ===  SQLITE && production()->driver() === SQLITE)
                    {
                        $prod = connect(SQLITE,strval(env('DB_NAME')));
                        $dev =  connect(SQLITE,strval(env('DEVELOP_DB_NAME')));

                        if ($prod->connected())
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }
                        if ($dev->connected())
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                    }


                }
                if (Web::generate())
                {
                    $io->success('The router base has been created successfully');
                }else{
                    $io->error('The creation of the route rbase has failed');
                    return 1;
                }

                if ((new CreateMigrationTable(production(), 'up', 'prod'))->up() && (new CreateMigrationTable(development(), 'up', 'dev'))->up())
                {
                    $io->success('All migrations tables has been created successfully');
                } else{
                    $io->error('Creation of the migrations table has failed');
                    return 1;

                }

                $io->success('All configuration has been executed successfully');

                return 0;
            }
            $io->warning('Nothing has been done ! Modify the .env file and try again');
            return 0;
        }


    }
}