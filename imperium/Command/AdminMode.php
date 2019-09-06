<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class AdminMode extends Command
		{
			
			protected static $defaultName = 'app:admin';
			
			protected function configure()
			{
				
				$this->setDescription('Turn application in AdminMode mode')->setAliases(['admin']);
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				if((new File(base('config') .DIRECTORY_SEPARATOR . 'mode.yaml',EMPTY_AND_WRITE_FILE_MODE))->write("mode: admin")->flush())
					$output->writeln("<info>Aplication is now in admin mode</info>");
			}
			
		}
	}
