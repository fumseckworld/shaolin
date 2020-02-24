<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\User\User;
        use Eywa\Exception\Kedavra;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class ShowUsers extends Command
		{
			
			protected static $defaultName = 'users:show';
			
			protected function configure()
			{
				$this->setDescription("List all users found in the base")->addArgument('env',InputArgument::REQUIRED,'The base environment');
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             * @return int
             * @throws Kedavra
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
		        $io = new SymfonyStyle($input,$output);

                $env = $input->getArgument('env');

                not_in(['dev','prod'],$env,true,'The env must be dev or prod');

                if (equal($env,'dev'))
                {
                    $x = (new User(development()))->show()->all();

                }else{
                    $x = (new User(production()))->show()->all();

                }
		        if (not_def($x))
                {
                    $io->error("No users found");
                    return 1;
                }
		        foreach ($x as $table)
                {
                    $io->success($table);
                }

		        return 0;
            }
			
		}
	}