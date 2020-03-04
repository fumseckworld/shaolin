<?php
	
	namespace Eywa\Console\App
	{

        use Eywa\Console\Shell;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;


        class Serve extends Command
		{
			
			protected static $defaultName = "app:run";
			
			
			protected function configure():void
			{
				$this->setDescription('Run a development server');
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

                $io->title('Started development server');
				$io->success("The server is running at : http://localhost:3000");

                (new Shell("php -S localhost:3000 -d display_errors=1 -t web"))->run();

               return 0;

			}
			
		}
	}