<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class Maintenance extends Command
		{
			
			protected static $defaultName = 'app:down';
			
			protected function configure()
			{
				
				$this->setDescription('Turn application in maintenance mode')->setAliases(['down']);
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				if((new File(base('config.yaml') .DIRECTORY_SEPARATOR . 'mode.yaml',EMPTY_AND_WRITE_FILE_MODE))->write("mode: down")->flush())
					$output->writeln("<info>Aplication is now in maintenance mode</info>");
			}
			
		}
	}
