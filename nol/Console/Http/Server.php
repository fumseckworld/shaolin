<?php

namespace Nol\Console\Http {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;
    use Symfony\Component\Process\Process;

    class Server extends Command
    {
        protected static $defaultName = 'app:run';

        protected function configure(): void
        {
            $this->setDescription('Start a development server');
        }

        /**
         * @param InputInterface  $input
         * @param OutputInterface $output
         *
         * @throws DependencyException
         * @throws NotFoundException
         * @return int|void
         */
        protected function execute(InputInterface $input, OutputInterface $output)
        {
            $x = Process::fromShellCommandline(
                sprintf(
                    'php -S localhost:%s -t %s',
                    app('server-port'),
                    app('public-dirname')
                )
            );

            $x->setTimeout(null)->setIdleTimeout(null)->enableOutput();
            $io = new SymfonyStyle($input, $output);
            $io->success(
                sprintf(
                    'Your website is now accessible at the url : http://localhost:%s',
                    app('server-port')
                )
            );
            return $x->run();
        }
    }
}
