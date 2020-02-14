<?php
	
	namespace Eywa\Console\App
	{


        use Eywa\Console\Shell;
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


                if (file_exists('dkim.private.key'))
                {
                    $io->error('The dkim keys already exist');
                    return 1;
                }

                if ((new Shell('openssl genrsa -out dkim.private.key 1024'))->run() && (new Shell('openssl rsa -in dkim.private.key -out dkim.public.key -pubout -outform PEM'))->run())
                {
                    $io->success('The dkim keys will be used to sign all emails');
                    return 0;
                }

                $io->error("Generation has failed");
                return 1;
            }
			
		}
	}
