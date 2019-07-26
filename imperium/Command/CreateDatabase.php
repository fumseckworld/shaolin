<?php


	namespace Imperium\Command
	{


		use Exception;
		use Imperium\Connexion\Connect;
		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		class CreateDatabase extends Command
		{
			protected static $defaultName = 'db:create';

			protected function configure()
			{
				$base = db('base');
				$this->setDescription("Create the $base database");
			}

			public function execute(InputInterface $input, OutputInterface $output)
			{
				$driver = db('driver');
				$db = db('base');

				if (has($driver, [MYSQL, POSTGRESQL]))
				{
					try
					{
						$connect = new Connect($driver, '', db('username'), db('password'), db('host'), '');

						$connect->execute("CREATE DATABASE IF NOT EXISTS $db");
						$output->write("<bg=green;fg=white>The $db base was created successfully\n");

					} catch (Exception $exception)
					{
						exit($exception->getMessage());
					}
					return 0;
				}

				$db = DB . DIRECTORY_SEPARATOR . $db;

				if (File::create($db))
					$output->write("<bg=green;fg=white>The $db base was created successfully\n");

				return 0;
			}

		}
	}
