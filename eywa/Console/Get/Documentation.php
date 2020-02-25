<?php
	
	namespace Eywa\Console\Get
	{

        use Eywa\Console\Shell;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;


        class Documentation extends Command
		{
			
			protected static $defaultName = "get:doc";
			
			
			protected function configure()
			{
				$this->setDescription('Get the eywa and shaolin documentation')->addArgument('directory',InputArgument::REQUIRED,'The documentation directory name');
			}
		
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $io = new SymfonyStyle($input,$output);

                $io->title('Downlaoding or updating the documentation');
                $dir = $input->getArgument('directory');
				if(is_dir($dir))
                {
                    if(chdir($dir))
                        $io->success('Checkout inside the documentation directory');

                    if ((new Shell('git pull origin master'))->run())
                    {
                        $io->success('Documentation is up to date');
                        return 0;
                    }else{
                        $io->error('We have not found git');
                        return 1;
                    }
                }else{
                    if ((new Shell("git clone https://github.com/fumseckworld/documentation.git $dir"))->run())
                    {
                        $io->success('Documentation has been downloaded successfully');
                        return 0;
                    }else{
                        $io->error('We have not found git');
                        return 1;
                    }
                }

			}
			
		}
	}