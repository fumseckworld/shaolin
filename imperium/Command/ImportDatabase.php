<?php
	
	namespace Imperium\Command
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Exception\Kedavra;
    use Imperium\Import\Import;
    use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class ImportDatabase extends Command
		{
			
			protected static $defaultName = 'db:import';
			
			/**
			 * @throws Kedavra
			 */
			protected function configure()
			{
				
				$base = db('base');
				$this->setAliases([ 'import' ]);
				$this->setDescription("Import sql file content into $base database");
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws DependencyException
			 * @throws NotFoundException
			 * @throws Kedavra
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				if((new Import())->import())
				{
					$output->writeln('<info>Sql file was imported successfully</info>');
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}