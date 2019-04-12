<?php

namespace Imperium\Command {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class MigrateDatabase extends Command
    {
        protected static $defaultName = 'db:migrate';
        protected function configure()
        {

            $this->setDescription('Execute all migrations');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            return system("./vendor/bin/phinx migrate -e development");
        }
    }
}