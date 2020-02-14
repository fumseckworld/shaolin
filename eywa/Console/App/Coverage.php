<?php
	
	namespace Eywa\Console\App
	{

        use Eywa\Console\Shell;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;


        class Coverage extends Command
		{
			
			protected static $defaultName = "app:coverage";
			
			
			protected function configure()
			{
				$this->setDescription('Run a server to show the code coverage');
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

                $io->title('Started coverage server');
				$io->success("The server is running at : http://localhost:8000");

                (new Shell("php -S localhost:8000 -t coverage"))->run();

               return 0;

			}
			
		}
	}