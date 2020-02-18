<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Table\Table;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class DropTable extends Command
		{
			
			protected static $defaultName = 'table:drop';
			
			protected function configure()
			{
                $this->setDescription("Drop a table")->addArgument('table',InputArgument::REQUIRED,'The table name')->addArgument('env',InputArgument::REQUIRED,'The base environment');

            }
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $env = $input->getArgument('env');

                not_in(['dev','prod','any'],$env,true,"Only dev, prod or any must be used");

		        $io = new SymfonyStyle($input,$output);

		        $x = $input->getArgument('table');

                if (equal($env, 'dev'))
                {
                    $table = new Table(development());

                    if (not_def($table->show()))
                    {
                        $io->error("No tables found");
                        return 1;
                    }

                    if (is_false($table->exist($x)))
                    {
                        $io->error("The $x table not exist");
                        return 1;
                    }
                    if ($table->from($x)->drop())
                    {
                        $io->success("The $x table was removed successfully");
                        return 0;
                    }else{
                        $io->error("Failed to remove the $x table");
                        return 1;
                    }
                }
		        if (equal($env, 'prod'))
                {
                    $table = new Table(production());

                    if (not_def($table->show()))
                    {
                        $io->error("No tables found");
                        return 1;
                    }

                    if (is_false($table->exist($x)))
                    {
                        $io->error("The $x table not exist");
                        return 1;
                    }
                    if ($table->from($x)->drop())
                    {
                        $io->success("The $x table was removed successfully");
                        return 0;
                    }else{
                        $io->error("Failed to remove the $x table");
                        return 1;
                    }
                }
               if (equal($env, 'any'))
                {
                    $table = new Table(development());

                    if (not_def($table->show()))
                    {
                        $io->error("No tables found");
                        return 1;
                    }

                    if (is_false($table->exist($x)))
                    {
                        $io->error("The $x table not exist");
                        return 1;
                    }
                    if ($table->from($x)->drop())
                    {
                        $io->success("The $x table was removed successfully");
                        return 0;
                    }else{
                        $io->error("Failed to remove the $x table");
                        return 1;
                    }

                    $table = new Table(production());

                    if (not_def($table->show()))
                    {
                        $io->error("No tables found");
                        return 1;
                    }

                    if (is_false($table->exist($x)))
                    {
                        $io->error("The $x table not exist");
                        return 1;
                    }
                    if ($table->from($x)->drop())
                    {
                        $io->success("The $x table was removed successfully");
                        return 0;
                    }else{
                        $io->error("Failed to remove the $x table");
                        return 1;
                    }
                }

		        $io->error("Environment not valid");
		        return 1;
            }
			
		}
	}