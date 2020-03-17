<?php
    
    namespace Eywa\Console\Get
    {

        use Eywa\Console\Shell;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class Wanted extends Command
        {
            protected static $defaultName = "get:wanted";
            
            
            protected function configure(): void
            {
                $this->setDescription('Get the wanted style')->addArgument('directory', InputArgument::REQUIRED, 'The documentation directory name');
            }
        
            
            /**
             * @param  InputInterface   $input
             * @param  OutputInterface  $output
             *
             * @return int|null
             */
            public function execute(InputInterface $input, OutputInterface $output)
            {
                $io = new SymfonyStyle($input, $output);

                $io->title('Downlaoding wanted');
                $dir = strval($input->getArgument('directory'));
                if (is_dir($dir)) {
                    $io->error('Wanted already exist');
                    return 1;
                } else {
                    if ((new Shell("git clone https://github.com/fumseckworld/wanted.git $dir"))->run()) {
                        $io->success('Wanted has been downloaded successfully');
                        return 0;
                    } else {
                        $io->error('We have not found git');
                        return 1;
                    }
                }
            }
        }
    }
