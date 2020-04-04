<?php

namespace Eywa\Console\Git {

    use Eywa\Exception\Kedavra;
    use Eywa\Versioning\Version;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GitCommit extends Command
    {
        protected static $defaultName = 'git:commit';


        protected function configure(): void
        {
            $this->setDescription('Run git commit interative command');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int|void
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $vesion = new Version($io);

            return $vesion->check()->diff()->add()->commit()->send()->success();
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->success('Bye');

            return 0;
        }
    }
}
