<?php
	
	namespace Eywa\Console\App
	{


        use Eywa\File\File;
        use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class Dkim extends Command
		{
			
			protected static $defaultName = 'dkim:generate';
			
			protected function configure()
			{
				$this->setDescription('Generate dkim keys');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
				$io = new SymfonyStyle($input,$output);

                $io->title('Generation of the dkim key');

                shell_exec("openssl genrsa -out dkim.private.key 1024");

                shell_exec('openssl rsa -in dkim.private.key -out dkim.public.key -pubout -outform PEM');

                if (File::exist('dkim.private.key', 'dkim.public.key'))
                {
                    $io->success('The dkim keys will be used to sign all emails');
                    return 0;
                }

                $io->error("Sorry generation has failed");
                return 1;
            }
			
		}
	}
