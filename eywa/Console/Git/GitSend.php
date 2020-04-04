<?php
    
namespace Eywa\Console\Git {
    use Eywa\Exception\Kedavra;
    use Eywa\Versioning\Version;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GitSend extends Command
    {
        protected static $defaultName = 'git:send';


        protected function configure(): void
        {
            $this->setDescription('Send the application to all remotes');
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

            return (new Version($io))->check()->send()->success();
        }
    }
}
