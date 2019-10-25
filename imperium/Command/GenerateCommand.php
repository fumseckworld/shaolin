<?php
	
	namespace Imperium\Command;

	use Imperium\Exception\Kedavra;
	use Imperium\File\File;
	use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	
	class GenerateCommand extends Command
	{
		
		protected static $defaultName = "make:command";
		
		protected function configure()
		{
			
			$this->setDescription('Generate a command')->addArgument('cmd', InputArgument::REQUIRED, 'the command name');
		}
		
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
		 * @throws Kedavra
		 * @return int|void|null
		 */
		public function execute(InputInterface $input, OutputInterface $output)
		{

		    $command  = ucfirst(strtolower(str_replace('Command','',$input->getArgument('cmd')))) .'Command';

			
			$file = base('app') . DIRECTORY_SEPARATOR . 'Console' .DIRECTORY_SEPARATOR . $command. '.php';

			if( ! file_exists($file))
			{
				if((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\n\t\tnamespace App\Console;\n\n\t\tuse Symfony\Component\Console\Command\Command;\n\t\tuse Symfony\Component\Console\Input\InputInterface;\n\t\tuse Symfony\Component\Console\Output\OutputInterface;\n\n\t\tclass {$command} extends Command\n\t\t{\n\n\t\t\tprotected static \$defaultName = '';\n\n\t\t\tprotected function configure()\n\t\t\t{\n\t\t\t\t\$this->setDescription('');\n\t\t\t}\n\n\t\t\tpublic function interact(InputInterface \$input, OutputInterface \$output)\n\t\t\t{\n\n\n\t\t\t}\n\n\t\t\tpublic function execute(InputInterface \$input, OutputInterface \$output)\n\t\t\t{\n\n\t\t\t\treturn 0;\n\t\t\t}\n\n\t\t}\n")->flush())
					$output->writeln('<info>The command has been generated successfully</info>');
				
				return 0;
			}
			else
			{
				$output->writeln('<bg=red;fg=white>The view already exist </>');
				
				return 1;
			}
		}
		
	}