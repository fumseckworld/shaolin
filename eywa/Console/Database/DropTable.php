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
                $this->setDescription("Drop a table")->addArgument('table',InputArgument::REQUIRED,'The table name');

            }
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
		        $io = new SymfonyStyle($input,$output);

		        $x = $input->getArgument('table');

		        $table = new Table();

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
                }

		        $io->error("Fail to remove the $x table");
		        return 1;
            }
			
		}
	}