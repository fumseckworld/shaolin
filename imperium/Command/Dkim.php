<?php

	namespace Imperium\Command
	{

		use Imperium\File\File;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;

		class Dkim extends Command
		{
			protected static $defaultName = 'dkim:generate';

			protected function configure()
			{
				$this->setDescription('Generate dkim keys');
			}

			public function execute(InputInterface $input, OutputInterface $output)
			{

				shell_exec("openssl genrsa -out dkim.private.key 1024");
				shell_exec('openssl rsa -in dkim.private.key -out dkim.public.key -pubout -outform PEM');

				return File::exist('dkim.private.key', 'dkim.public.key');
			}
		}
	}
