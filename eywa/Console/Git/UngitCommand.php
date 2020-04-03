<?php

namespace Eywa\Console\Git {

    use Eywa\Console\Shell;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UngitCommand extends Command
    {
        protected static $defaultName = 'git:hub';


        protected function configure(): void
        {
            $this->setDescription('Start ungit');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->success('Wake up ungit');

            (new Shell('ungit'))->get()->setTimeout(null)->run();
            return 0;
        }
    }
}
