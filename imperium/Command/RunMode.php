<?php


	namespace Imperium\Command
	{

		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		class RunMode extends Command
		{
			protected static $defaultName = 'app:up';


			protected function configure()
			{
				$this->setDescription('Turn the application in the  mode')->setAliases(['up']);
			}

			public function execute(InputInterface $input, OutputInterface $output)
			{

				return File::put("web/index.php", "<?php\n\n\trequire_once dirname(__DIR__) . '/vendor/autoload.php';\n\n\techo app()->run();") === true;
			}

		}
	}