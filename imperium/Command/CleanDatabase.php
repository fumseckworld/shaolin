<?php

namespace Imperium\Command {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class CleanDatabase extends Command
    {
        protected static $defaultName = 'db:clean';
        protected function configure()
        {
            $this->setAliases(['C']);

            $base = config('db','base');

            $this->setDescription("Clean the $base database");
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            $base = config('db','base');

            $app = app();

            is_false(app()->bases()->drop($base),true,'Failed to remove database');

            is_false( $app->bases()->create($base),true,'Failed to create the database');

            $output->write("<bg=green;fg=white>The $base database was cleaned successfully\n");


            return 0;
        }
    }
}