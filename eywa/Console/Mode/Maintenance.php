<?php
	
	namespace Eywa\Console\Mode
	{
        use Eywa\Exception\Kedavra;
        use Eywa\File\File;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class Maintenance extends Command
		{
			
			protected static $defaultName = 'app:down';
			
			protected function configure()
			{
				$this->setDescription('Put the application in maintenance mode');
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             * @return int|void
             * @throws Kedavra
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $io = new SymfonyStyle($input,$output);

                $io->title('Enabling the maintenance mode');
				if((new File('config/mode.yaml',EMPTY_AND_WRITE_FILE_MODE))->write("mode: down\nconnexion: prod")->flush())
                {
                    $io->success('The application is now in maintenance mode');
                    return 0;
                }

				$io->error('Checkout mode has failed');
				return 1;
			}
			
		}
	}
