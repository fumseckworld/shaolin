<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Connexion\Connect;
        use Eywa\Database\Table\Table;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class TruncateTable extends Command
		{
			
			protected static $defaultName = 'table:clear';
			
			protected function configure()
			{
				$this->setDescription("Truncate a table")->addArgument('table',InputArgument::REQUIRED,'The table name')->addArgument('env',InputArgument::REQUIRED,'The base environment');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
		        $io = new SymfonyStyle($input,$output);

                $env = $input->getArgument('env');
                $table = $input->getArgument('table');
                not_in(['dev','prod'],$env,true,'The environement used is not valid');

                if (equal($env,'dev'))
                {
                    $success = (new Table(development()))->from($table)->truncate();
                }else{

                    $success = (new Table(production()))->from($table)->truncate();
                }


		        if ($success)
                {
                    $io->success("The $table table has been successfully truncated");
                    return 0;

                }
                $io->error("The truncate task for the $table table has failed");
                return 1;
            }
			
		}
	}