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

    class UninstallDatabase extends Command
    {
        protected static $defaultName = 'db:uninstall';
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

        protected function configure()
        {
            $this->setDescription('Reverse the install command');
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

            $helper = $this->getHelper('question');

            $dev_base_created_successfully = 'The development database has been removed successfully';
            $prod_base_created_successfully = 'The production database has been removed successfully';
            $dev_user_created_successfully = 'The development user has been removed successfully';
            $prod_user_created_successfully = 'The production user has been removed successfully';

            $dev_base_created_fail = 'The development database has been  removed successfully';
            $prod_base_created_fail = 'The production database has been removed successfully created';
            $dev_user_created_fail = 'The development user has been removed successfully created';
            $prod_user_created_fail = 'The production user has been removed successfully created';

            if (in_array(development()->driver(), [MYSQL, POSTGRESQL]) && in_array(production()->driver(),[MYSQL,POSTGRESQL]))
            {


                $io->title('Creating all databases');

                if (development()->driver() === MYSQL && production()->driver() === MYSQL)
                {

                    do
                    {
                        $question = new Question('Please enter the root password : ', 'root');

                        $question->setHidden(true);

                        $this->pass = $helper->ask($input, $output, $question) ?? 'root';

                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                    $dev = connect(MYSQL,'','root',$this->pass);

                    $prod = connect(MYSQL,'','root',$this->pass);


                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    } else {
                        $io->error($dev_base_created_fail);
                        return 1;
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {

                        $io->success($prod_base_created_successfully);

                    } else {
                        $io->error($prod_base_created_fail);
                        return 1;
                    }

                    $dev = connect(MYSQL,'','root',$this->pass);

                    $prod = connect(MYSQL,'','root',$this->pass);

                    if ($prod->remove_user(env('DB_USERNAME')))
                    {

                        $io->success($prod_user_created_successfully);

                    } else {
                        $io->error($prod_user_created_fail);
                        return 1;
                    }
                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
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
                        $question = new Question('Please enter the root password : ', 'root');
                        $question->setHidden(true);
                        $this->mysql_pass = $helper->ask($input, $output, $question) ?? 'root';

                    } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());

                    do
                    {
                        $question = new Question('Please enter the postgres password : ', 'postgres');
                        $question->setHidden(true);
                        $this->pgsql_pass = $helper->ask($input, $output, $question) ?? 'postgres';

                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                    $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                    $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    } else {
                        $io->error($dev_base_created_fail);
                        return 1;
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    } else {
                        $io->error($prod_base_created_fail);
                        return 1;
                    }

                    $dev = connect(MYSQL, '', 'root', $this->mysql_pass);
                    $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
                    {
                        $io->success($dev_user_created_successfully);
                    } else {
                        $io->error($dev_user_created_fail);
                        return 1;

                    }



                    if ($prod->remove_user(env('DB_USERNAME')))
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
                        $question = new Question('Please enter the root password : ', 'root');

                        $question->setHidden(true);

                        $this->mysql_pass = $helper->ask($input, $output, $question) ?? 'root';

                    } while (!connect(MYSQL, '', 'root', $this->mysql_pass)->connected());

                    do
                    {
                        $question = new Question('Please enter the postgres password : ', 'postgres');

                        $question->setHidden(true);

                        $this->pgsql_pass = $helper->ask($input, $output, $question) ?? 'postgres';

                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                    $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                    $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    } else {
                        $io->error($dev_base_created_fail);
                        return 1;
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    } else {
                        $io->error($prod_base_created_fail);
                        return 1;
                    }
                    $dev =  connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);
                    $prod = connect(MYSQL, '', 'root', $this->mysql_pass);

                    if ($prod->remove_user(env('DB_USERNAME')))
                    {
                        $io->success($prod_user_created_successfully);
                    } else {
                        $io->error($prod_user_created_fail);
                        return 1;

                    }
                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
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
                        $question = new Question('Please enter the postgres password : ', 'postgres');

                        $question->setHidden(true);

                        $this->pgsql_pass = $helper->ask($input, $output, $question) ?? 'postgres';

                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432)->connected());

                    $dev = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    } else {
                        $io->error($dev_base_created_fail);
                        return 1;
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    } else {
                        $io->error($prod_base_created_fail);
                        return 1;
                    }

                    $dev = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    $prod = connect(POSTGRESQL, '', 'postgres', $this->pgsql_pass,5432);

                    if ($prod->remove_user(env('DB_USERNAME')))
                    {
                        $io->success($prod_user_created_successfully);
                    } else {
                        $io->error($prod_user_created_fail);
                        return 1;

                    }

                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
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
                        $question = new Question('Please enter the root password : ', 'root');

                        $question->setHidden(true);

                        $this->pass = $helper->ask($input, $output, $question) ?? 'root';

                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());


                    $prod = connect(MYSQL, '', 'root', $this->pass);

                    if (unlink(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    }else{
                        $io->error($dev_base_created_fail);
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    }else{
                        $io->error($prod_base_created_fail);
                    }

                    $prod = connect(MYSQL, '', 'root', $this->pass);
                    if ($prod->remove_user(env('DB_USERNAME')))
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
                        $question = new Question('Please enter the root password : ', 'root');

                        $question->setHidden(true);

                        $this->pass = $helper->ask($input, $output, $question) ?? 'root';

                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                    $dev = connect(MYSQL,'','root',$this->pass);



                    if (unlink(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    }else{
                        $io->error($prod_base_created_fail);
                    }

                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    }else{
                        $io->error($dev_base_created_fail);
                    }
                    $dev = connect(MYSQL,'','root',$this->pass);
                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
                    {
                        $io->success($dev_user_created_successfully);
                    }else{
                        $io->error($dev_user_created_fail);
                    }
                }

                if (development()->driver() ===  SQLITE && production()->driver() === POSTGRESQL)
                {
                    do {
                        $question = new Question('Please enter the postgres password : ', 'postgres');

                        $question->setHidden(true);

                        $this->pass = $helper->ask($input, $output, $question) ?? 'postgres';

                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pass,5432)->connected());

                    $prod = connect(POSTGRESQL,'','postgres',$this->pass,5432);



                    if (unlink(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    }else{
                        $io->error($dev_base_created_fail);
                    }

                    if ($prod->remove_database(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    }else{
                        $io->error($prod_base_created_fail);
                    }
                    $prod = connect(POSTGRESQL,'','postgres',$this->pass,5432);
                    if ($prod->remove_user(env('DB_USERNAME')))
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
                        $question = new Question('Please enter the postgres password : ', 'postgres');

                        $question->setHidden(true);

                        $this->pass = $helper->ask($input, $output, $question) ?? 'postgres';

                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pass,5432)->connected());


                    $dev = connect(POSTGRESQL, '','postgres',$this->pass,5432);

                    if (unlink(env('DB_NAME')))
                    {
                        $io->success($prod_base_created_successfully);
                    }else{
                        $io->error($prod_base_created_fail);
                    }

                    if ($dev->remove_database(env('DEVELOP_DB_NAME')))
                    {
                        $io->success($dev_base_created_successfully);
                    }else{
                        $io->error($dev_base_created_fail);
                    }
                    $dev = connect(POSTGRESQL, '','postgres',$this->pass,5432);
                    if ($dev->remove_user(env('DEVELOP_DB_USERNAME')))
                    {
                        $io->success($dev_user_created_successfully);
                    }else{
                        $io->error($dev_user_created_fail);
                    }

                }
                if (development()->driver() ===  SQLITE && production()->driver() === SQLITE)
                {



                    if (unlink((env('DB_NAME'))))
                    {
                        $io->success($prod_base_created_successfully);
                    }else{
                        $io->error($prod_base_created_fail);
                    }
                    if (unlink(env('DEVELOP_DB_NAME')))
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


    }
}