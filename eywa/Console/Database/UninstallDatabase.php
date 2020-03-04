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

        /**
         *
         * The root password
         *
         */
        private string $pass = '';

        /**
         *
         * The mysql root password
         *
         */
        private string $mysql_pass = '';

        /**
         *
         * The pgsql postgres password
         *
         */
        private string $pgsql_pass = '';

        protected function configure():void
        {
            $this->setDescription('Reverse the install command');
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         *
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);


            $dev_base_created_successfully = 'The development database has been removed successfully';
            $prod_base_created_successfully = 'The production database has been removed successfully';
            $dev_user_created_successfully = 'The development user has been removed successfully';
            $prod_user_created_successfully = 'The production user has been removed successfully';

            $dev_base_created_fail = 'The development database has been  removed successfully';
            $prod_base_created_fail = 'The production database has been removed successfully created';
            $dev_user_created_fail = 'The development user has been removed successfully created';
            $prod_user_created_fail = 'The production user has been removed successfully created';

            $prod = production()->info();
            $dev = development()->info();
            $mysql_root_password_question = "What it's the password for the MySQL root user ?";
            $pgsql_root_password = "What it's the password for the postgreSQL postgres user ?";
            if($io->confirm("Are you sure to remove the development database ? \n\n<fg=black;bg=yellow>$dev</>",false) && $io->confirm("Are you sure to remove the production database ? \n\n<fg=black;bg=yellow>$prod</>",false))
            {

                if (in_array(development()->driver(), [MYSQL, POSTGRESQL]) && in_array(production()->driver(),[MYSQL,POSTGRESQL]))
                {

                    $io->title('Removing users and databases');

                    if (development()->driver() === MYSQL && production()->driver() === MYSQL)
                    {

                        do
                        {
                            $this->pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        }while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                        $dev = connect(MYSQL,'','root',$this->pass);

                        $prod = connect(MYSQL,'','root',$this->pass);


                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {

                            $io->success($prod_base_created_successfully);

                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(MYSQL,'','root',$this->pass);

                        $prod = connect(MYSQL,'','root',$this->pass);

                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
                        {

                            $io->success($prod_user_created_successfully);

                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;
                        }
                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
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
                            $this->pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());

                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                        $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        } else {
                            $io->error($dev_user_created_fail);
                            return 1;

                        }



                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
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
                            $this->pass  =  $io->askQuestion((new Question($mysql_root_password_question, 'root'))->setHidden(true));

                        } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());

                        do
                        {
                            $this->pgsql_pass  =  $io->askQuestion((new Question($pgsql_root_password, 'postgres'))->setHidden(true));

                        } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());


                        $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }
                        $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
                        {
                            $io->success($prod_user_created_successfully);
                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;

                        }
                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
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

                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        } else {
                            $io->error($dev_base_created_fail);
                            return 1;
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        } else {
                            $io->error($prod_base_created_fail);
                            return 1;
                        }

                        $dev = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
                        {
                            $io->success($prod_user_created_successfully);
                        } else {
                            $io->error($prod_user_created_fail);
                            return 1;

                        }

                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
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




                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                        if (unlink(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        $prod = connect(MYSQL, '', 'root', $this->mysql_pass);
                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
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



                        if (unlink(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                        $dev = connect(MYSQL,'','root',$this->mysql_pass);
                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
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



                        if (unlink(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }

                        if ($prod->remove_database(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }
                        $prod = connect(POSTGRESQL,'','postgres',$this->pgsql_pass,5432);
                        if ($prod->remove_user(strval(env('DB_USERNAME'))))
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


                        $dev = connect(POSTGRESQL, '','postgres',$this->pgsql_pass,5432);

                        if (unlink(strval(env('DB_NAME'))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }

                        if ($dev->remove_database(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                        $dev = connect(POSTGRESQL, '','postgres',$this->pgsql_pass,5432);
                        if ($dev->remove_user(strval(env('DEVELOP_DB_USERNAME'))))
                        {
                            $io->success($dev_user_created_successfully);
                        }else{
                            $io->error($dev_user_created_fail);
                        }

                    }
                    if (development()->driver() ===  SQLITE && production()->driver() === SQLITE)
                    {



                        if (unlink((strval(env('DB_NAME')))))
                        {
                            $io->success($prod_base_created_successfully);
                        }else{
                            $io->error($prod_base_created_fail);
                        }
                        if (unlink(strval(env('DEVELOP_DB_NAME'))))
                        {
                            $io->success($dev_base_created_successfully);
                        }else{
                            $io->error($dev_base_created_fail);
                        }
                    }


                }
                $io->success('All databases and users has been removed successfully');

                return 0;
            }
            $io->warning('Nothing has been done');

            return 0;
        }


    }
}