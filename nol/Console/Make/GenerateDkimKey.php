<?php

namespace Nol\Console\Make {

    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;
    use Symfony\Component\Process\Process;

    class GenerateDkimKey extends Command
    {
        protected static $defaultName = 'make:dkim';

        protected function configure(): void
        {
            $this->setDescription('Generate dkim keys');
        }

        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $x = Process::fromShellCommandline(
                'openssl genrsa -out dkim.private.key 1024 &&
                 openssl rsa -in dkim.private.key -out dkim.public.key -pubout -outform PEM'
            );

            $io = new SymfonyStyle($input, $output);

            $x->run();
            if ($x->isSuccessful()) {
                $io->success('The dkim keys has been generated successfully');
                return 0;
            }
            $io->error('Dkim generation keys has failed');
            return 1;
        }
    }
}
