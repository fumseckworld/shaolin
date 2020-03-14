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

        private string $pass ='';


        private string $mysql_root_password_question = 'What it\'s the password for the MySQL root user ?';

        private string $pgsql_root_password = 'What it\'s the password for the postgreSQL postgres user ?';

        private string $prod_user_created_fail = 'The production user has been created successfully';

        private string $dev_user_created_fail = 'The development user has been created successfully';

        private string $prod_base_created_fail = 'The production database has been created successfully';

        private string $dev_base_created_fail = 'The development database has been created successfully';

        private string $prod_user_created_successfully = 'The production user has been created successfully';

        private string $dev_user_created_successfully = 'The development user has been created successfully';

        private string $prod_base_created_successfully = 'The production database has been created successfully';

        private string $dev_base_created_successfully = 'The development database has been created successfully';

        private string $routing_instance_created_successfully = 'The routing database has been created successfully';

        private string $routing_instance_creation_has_fail ='The creation of the routing database has failed please check if sqlite are running';
        private string $migration_tables_created_successfully ='All migrations tables has been created successfully';
        private string $migration_tables_created_failed ='Creation of the migrations table has failed';

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



            $prod = production()->info();
            $dev = development()->info();

            if($io->confirm(sprintf('Are you sure to use the following as a production database ? <fg=black;bg=yellow>%s</>',$prod),false) && $io->confirm(sprintf('Are you sure to use the following as a development database ? <fg=black;bg=yellow>%s</>',$dev),false))
            {
                return  $this->create(strval(env('DEVELOP_DB_DRIVER')),strval(env('DB_DRIVER')),$io);
            }
            $io->warning('Nothing has been done ! Modify the .env file and try again');
            return 0;
        }


        /**
         * @param string $dev
         * @param string $prod
         * @param SymfonyStyle $io
         * @return int
         * @throws Kedavra
         * @throws Exception
         */

        private function create(string $dev,string $prod,SymfonyStyle $io):int
        {

            if (!is_dir(base('routes')))
                mkdir(base('routes'));

            switch ($dev)
            {
                case MYSQL:
                    do
                    {
                        $this->pass  =  $io->askQuestion((new Question($this->mysql_root_password_question, 'root'))->setHidden(true));
                    } while (!connect(MYSQL, '', 'root', $this->pass)->connected());

                    if(connect(MYSQL,'','root',$this->pass)->create_database(strval(env('DEVELOP_DB_NAME'))))
                    {
                        $io->success($this->dev_base_created_successfully);

                    }   else{
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }

                    if( connect(MYSQL,'','root',$this->pass)->create_user(strval(env('DEVELOP_DB_USERNAME')),strval(env('DEVELOP_DB_PASSWORD')),strval(env('DEVELOP_DB_NAME'))))
                    {
                        $io->success($this->dev_user_created_successfully);

                    }   else{
                        $io->error($this->dev_user_created_fail);
                        return 1;
                    }
                break;

                case POSTGRESQL:
                    do
                    {
                        $this->pass  =  $io->askQuestion((new Question($this->pgsql_root_password, 'postgres'))->setHidden(true));
                    } while (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected());

                    if( connect(POSTGRESQL,'','postgres',$this->pass)->create_database(strval(env('DEVELOP_DB_NAME'))))
                    {
                        $io->success($this->dev_base_created_successfully);

                    }   else{
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }

                    if( connect(POSTGRESQL,'','postgres',$this->pass)->create_user(strval(env('DEVELOP_DB_USERNAME')),strval(env('DEVELOP_DB_PASSWORD')),strval(env('DEVELOP_DB_NAME'))))
                    {
                        $io->success($this->dev_user_created_successfully);

                    }   else{
                        $io->error($this->dev_user_created_fail);
                        return 1;
                    }


                break;
                default:
                    if (connect(SQLITE,strval(env('DEVELOP_DB_NAME')))->connected())
                    {
                        $io->success($this->dev_base_created_successfully);
                    }else{
                        $io->error($this->dev_base_created_fail);
                        return 1;
                    }
                break;

            }

            switch ($prod)
            {
                case MYSQL :

                    if (!connect(MYSQL, '', 'root', $this->pass)->connected())
                    {
                        do
                        {
                            $this->pass  =  $io->askQuestion((new Question($this->mysql_root_password_question, 'root'))->setHidden(true));
                        } while (!connect(MYSQL, '', 'root', $this->pass)->connected());
                    }


                    if(connect(MYSQL,'','root',$this->pass)->create_database(strval(env('DB_NAME'))))
                    {
                        $io->success($this->prod_base_created_successfully);

                    }   else{
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }

                    if(connect(MYSQL,'','root',$this->pass)->create_user(strval(env('DB_USERNAME')),strval(env('DB_PASSWORD')),strval(env('DB_NAME'))))
                    {
                        $io->success($this->prod_user_created_successfully);

                    }   else{
                        $io->error($this->prod_user_created_fail);
                        return 1;
                    }
                  break;

                case POSTGRESQL:

                    if (!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected())
                    {
                        do
                        {
                            $this->pass = $io->askQuestion((new Question($this->mysql_root_password_question, 'postgres'))->setHidden(true));
                        }while(!connect(POSTGRESQL, '', 'postgres', $this->pass)->connected());
                    }
                    if( connect(POSTGRESQL,'','postgres',$this->pass)->create_database(strval(env('DB_NAME'))))
                    {
                        $io->success($this->prod_base_created_successfully);

                    }   else{
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }

                    if( connect(POSTGRESQL,'','postgres',$this->pass)->create_user(strval(env('DB_USERNAME')),strval(env('DB_PASSWORD')),strval(env('DB_NAME'))))
                    {
                        $io->success($this->prod_user_created_successfully);

                    }   else{
                        $io->error($this->prod_user_created_fail);
                        return 1;
                    }

                break;
                default:
                    if (connect(SQLITE,strval(env('DEVELOP_DB_NAME')))->connected())
                    {
                        $io->success($this->prod_base_created_successfully);
                    }else{
                        $io->error($this->prod_base_created_fail);
                        return 1;
                    }
                break;
            }

            if (connect(SQLITE,base('routes','web.sqlite3'))->connected())
            {
                $io->success($this->routing_instance_created_successfully);

            }   else
            {
                $io->error($this->routing_instance_creation_has_fail);
                return 1;
            }
            if ((new CreateMigrationTable( 'up', 'prod'))->up() && (new CreateMigrationTable('up', 'dev'))->up())
            {
                $io->success($this->migration_tables_created_successfully);
            } else{
                $io->error($this->migration_tables_created_failed);
                return 1;

            }
            $io->success('The app are ready');
            return 0;

        }

    }
}