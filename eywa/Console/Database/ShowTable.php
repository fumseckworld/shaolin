<?php
	
	namespace Eywa\Console\Database
	{

        use Eywa\Database\Table\Table;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Style\SymfonyStyle;

        class ShowTable extends Command
		{
			
			protected static $defaultName = 'table:list';
			
			protected function configure()
			{
				$this->setDescription("List all tables found in the base")->addArgument('env',InputArgument::REQUIRED,'The base environment');
			}
			
			public function execute(InputInterface $input, OutputInterface $output)
			{
		        $io = new SymfonyStyle($input,$output);

		        $table = new \Symfony\Component\Console\Helper\Table($output);
		        $table->setStyle('box')->setHeaders(['name','records']);

                $env = $input->getArgument('env');

                not_in(['dev','prod'],$env,true,'The env must be dev or prod');

                if (equal($env,'dev'))
                {
                    $x = (new Table(development()))->show();


                }else{

                    $x = (new Table(production()))->show();

                }
		        if (not_def($x))
                {
                    $io->error("No tables found");
                    return 1;
                }


		        $table->setRows($x);

		    $table->render();

		        return 0;
            }
			
		}
	}