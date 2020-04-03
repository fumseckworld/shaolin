<?php

namespace Eywa\Console\Git {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GitBlame extends Command
    {
        protected static $defaultName = 'git:blame';


        protected function configure(): void
        {
            $this->setDescription('Run interactive git blame')
            ->addArgument('months', InputArgument::OPTIONAL, 'The commits months');
        }

        public function interact(InputInterface $input, OutputInterface $output): int
        {
            $io = new SymfonyStyle($input, $output);

            $files = [];
            exec('git ls-files', $files);
            do {
                $file =   $io->askQuestion((new Question('Type the filename '))->setAutocompleterValues($files));
                if (file_exists($file)) {
                    $io->warning(strval(shell_exec(sprintf('git blame %s', strval($file)))));
                }
            } while ($io->confirm('Continue ?', true));

            return 0;
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            $io->success('Bye');
            return 0;
        }
    }
}
