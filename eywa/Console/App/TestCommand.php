<?php
    
    namespace Eywa\Console\App
    {
        use Eywa\Console\Shell;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class TestCommand extends Command
        {
            protected static $defaultName = 'app:test';
            
            protected function configure():void
            {
                $this->setDescription('Test the application');
            }
            
            public function execute(InputInterface $input, OutputInterface $output)
            {
                $io = new SymfonyStyle($input, $output);

                echo shell_exec(base('vendor', 'bin', 'grumphp') . ' run');

                $io->success('Bye');

                return 0;
            }
        }
    }
