<?php

namespace Imperium\Command {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class DumpDatabase extends Command
    {
        protected static $defaultName = 'db:dump';
        protected function configure()
        {

            $base = db('base');
            $this->setAliases(['dump']);
            $this->setDescription("Dump the $base database");
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            return app()->model()->dump_base();

        }
    }
}