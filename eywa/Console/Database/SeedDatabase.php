<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Seed\Seeding;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
        use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class SeedDatabase extends Command
		{
			
			protected static $defaultName = 'db:seed';
			
			protected function configure()
			{
				$this->setDescription("Seed the development database");
			}
			
			public function execute(InputInterface $input,  OutputInterface $output)
			{
                $io = new SymfonyStyle($input,$output);

                if (Seeding::run($input,$output))
                {
                    $io->success("The database has been seeded");
                }

				return 0;
			}
			
		}
	}