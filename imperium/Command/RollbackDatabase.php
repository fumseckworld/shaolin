<?php

namespace Imperium\Command {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class RollbackDatabase extends Command
    {
        protected static $defaultName = 'db:rollback';
        protected function configure()
        {

            $this->setAliases(['R']);
            $this->setDescription('Rollback the latest migration');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            return system("./vendor/bin/phinx rollback -e development");

        }
    }
}