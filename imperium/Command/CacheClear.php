<?php

namespace Imperium\Command {

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class CacheClear extends Command
    {
        protected static $defaultName = 'cache:clear';
        protected function configure()
        {

            $this->setDescription('Clear the application cache');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

                 app()->cache()->clear();
                return not_def(app()->cache()->infos()->get('cache_list'));
        }
    }
}