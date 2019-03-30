<?php


namespace Imperium\Command {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class CreateDatabase extends Command
    {
        protected static $defaultName = 'db:create';

        protected function configure()
        {
            $this->setAliases(['G']);

            $base = config('db','base');
            $this->setDescription("Create the $base database");
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $driver =  config('db','driver');
            $db = config('db','base');

            if (has($driver,[MYSQL,POSTGRESQL]))
            {
                try
                {
                    $connect = new Connect($driver,'',config('db','username'),config('db','password'),config('db','host'),'');
                    $connect->execute("CREATE DATABASE IF NOT EXISTS $db");
                    $output->write("<bg=green;fg=white>The $db base was created successfully\n");

                }catch (Exception $exception)
                {
                    exit($exception->getMessage());
                }
                return 0;
            }
            $db = dirname(core_path(collection(config('app','dir'))->get('app'))) .DIRECTORY_SEPARATOR .'db'. $db;

            if(File::create($db))
                $output->write("<bg=green;fg=white>The $db base was created successfully\n");

            return 0;
        }

    }
}