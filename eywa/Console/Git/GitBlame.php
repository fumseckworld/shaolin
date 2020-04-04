<?php

namespace Eywa\Console\Git {


    use Eywa\Exception\Kedavra;
    use Eywa\Versioning\Version;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GitBlame extends Command
    {
        protected static $defaultName = 'git:blame';


        protected function configure(): void
        {
            $this->setDescription('Run interactive git blame');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output): int
        {
            $io = new SymfonyStyle($input, $output);

            return (new Version($io))->blame()->success();
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            return 0;
        }
    }
}
