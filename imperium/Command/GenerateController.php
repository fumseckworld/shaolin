<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputArgument;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class GenerateController extends Command
		{
			
			protected static $defaultName = 'make:controller';
			
			protected function configure()
			{
				
				$this
					// the short description shown while running "php bin/console list"
					->setDescription('Create a new controller')
					// the full command description shown when running the command with
					// the "--help" option
					->setHelp('php shaolin make:controller controller_name')->addArgument('controller', InputArgument::REQUIRED, 'The controller name.');
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				$controller = ucfirst(str_replace('Controller', '', $input->getArgument('controller')));
				append($controller, 'Controller');
				$controllers = CONTROLLERS;
				$namespace = 'App' . '\\' . 'Controllers';
				$file = $controllers . DIRECTORY_SEPARATOR . $controller . '.php';
				if(file_exists($file))
				{
					$output->write("<bg=red;fg=white>The $controller controller already exist\n");
					
					return 1;
				}
				if((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace { \n\n\tuse Imperium\Controller\Controller;\n\n\tClass $controller extends Controller\n\t{\n\n\t\tpublic function before_action()\n\t\t{\n\n\t\t}\n\n\t\tpublic function after_action()\n\t\t{\n\n\t\t}\n\n\t}\n\n}\n")->flush())
				{
					$output->write("<info>The $controller controller was generated successfully</info>\n");
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}