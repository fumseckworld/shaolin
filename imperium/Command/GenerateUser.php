<?php


	namespace Imperium\Command
	{


		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputArgument;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		class GenerateUser extends Command
		{
			protected static $defaultName = 'user:create';

			protected function configure()
			{
				$this
					// the short description shown while running "php bin/console list"
					->setDescription('Create a new user')
					// the full command description shown when running the command with
					// the "--help" option
					->addArgument('username', InputArgument::REQUIRED, 'The username.')->addArgument('password', InputArgument::REQUIRED, 'The password.');
			}

			public function execute(InputInterface $input, OutputInterface $output)
			{
				if (app()->model()->is_sqlite())
				{
					$output->write('Sqlite has not user');
					return 1;
				}
				return add_user($input->getArgument('username'), $input->getArgument('password'));
			}

		}
	}