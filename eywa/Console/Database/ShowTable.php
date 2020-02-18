<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Table\Table;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class ShowTable extends Command
		{
			
			protected static $defaultName = 'table:list';
			
			protected function configure()
			{
				$this->setDescription("List all tables found in the base");
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
		        $io = new SymfonyStyle($input,$output);


		        $x = (new Table())->show();
		        if (not_def($x))
                {
                    $io->error("No tables found");
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