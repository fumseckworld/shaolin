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
			
			protected function configure()
			{
				$this->setDescription('Test the application');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{

				$io = new SymfonyStyle($input,$output);


                if (is_dir('vendor'))
                {
                    if (is_dir('vendor'.DIRECTORY_SEPARATOR.'phpunit'))
                    {

                        $io->success('Starting all tests');
                        if((new Shell( base('vendor','bin','phpunit') . ' --coverage-html coverage'))->run())
                        {
                            $io->success('Congratulations no errors has been found');
                            return 0;
                        }
                        $io->error('Errors has been found, please check your code before send your application');
                        return 1;


                    }else{
                        $io->error('phpunit not installed');
                        return 1;
                    }

                }
                $io->error('run composer install');
                return 1;
            }

		}
	}
