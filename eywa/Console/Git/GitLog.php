<?php

namespace Eywa\Console\Git {


    use Eywa\Exception\Kedavra;
    use Eywa\Versioning\Version;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GitLog extends Command
    {
        protected static $defaultName = 'git:log';


        protected function configure(): void
        {
            $this->setDescription('Show git logs by months')
            ->addArgument('months', InputArgument::OPTIONAL, 'The commits months');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $size = def($input->getArgument('months')) ? intval($input->getArgument('months')) : 1;

            return (new Version($io))->logs($size);
        }
    }
}
