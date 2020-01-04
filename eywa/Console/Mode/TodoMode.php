<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		
		class TodoMode extends Command
		{
			
			protected static $defaultName = 'app:todo';
			
			protected function configure()
			{
				
				$this->setDescription('Turn application in todo  mode')->setAliases(['todo']);
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				if((new File(base('config.yaml') .DIRECTORY_SEPARATOR . 'mode.yaml',EMPTY_AND_WRITE_FILE_MODE))->write("mode: todo")->flush())
					$output->writeln("<info>Aplication is now in todo mode</info>");
			}
			
		}
	}
