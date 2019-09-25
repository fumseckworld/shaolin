<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Exception\Kedavra;
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputArgument;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class GenerateMiddleware extends Command
		{
			
			protected static $defaultName = 'make:middleware';
			
			protected function configure()
			{
				
				$this
					// the short description shown while running "php bin/console list"
					->setDescription('Create a new middleware')
					// the full command description shown when running the command with
					// the "--help" option
					->setHelp('php shaolin make:middleware name')->addArgument('middleware', InputArgument::REQUIRED, 'The middleware name.');
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
				$x = ucfirst($input->getArgument('middleware'));
				
				
				$namespace = 'App\Middleware';
				
				$file = 'app' .DIRECTORY_SEPARATOR.'Middleware'. DIRECTORY_SEPARATOR . $x . '.php';
				
				if(file_exists($file))
				{
					$output->write("<bg=red;fg=white>The $x middleware already exist\n");
					
					return 1;
				}
				if((new File($file, EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\nnamespace $namespace;\n\n\tuse GuzzleHttp\Psr7\Response;\n\tuse Imperium\Middleware\Middleware;\n\tuse Psr\Http\Message\ResponseInterface;\n\tuse Psr\Http\Message\ServerRequestInterface;\n\n\tclass AppMiddleware implements Middleware\n\t{\n\n\t\tpublic function handle(ServerRequestInterface \$request) : ResponseInterface\n\t\t{\n\n\t\t}\n\t}\n")->flush())
				{
					$output->write("<info>The $x middleware was generated successfully</info>\n");
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}