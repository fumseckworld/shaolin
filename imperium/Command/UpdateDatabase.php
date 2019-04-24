<?php

namespace Imperium\Command {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class UpdateDatabase extends Command
    {
        protected static $defaultName = 'db:up';
        private $base;

        protected function configure()
        {

            $this->base = config('db','base');

            $this->setDescription("Update the $this->base database")->setAliases(['update']);
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            return app()->model()->import($this->base);

        }
    }
}