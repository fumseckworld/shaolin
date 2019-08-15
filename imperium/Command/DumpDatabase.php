<?php
	
	namespace Imperium\Command
	{
		
		use DI\DependencyException;
		use DI\NotFoundException;
		use Imperium\Exception\Kedavra;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class DumpDatabase extends Command
		{
			
			protected static $defaultName = 'db:dump';
			
			/**
			 * @throws Kedavra
			 */
			protected function configure()
			{
				
				$base = db('base');
				$this->setAliases([ 'dump' ]);
				$this->setDescription("Dump the $base database");
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
				
				if(app()->save())
				{
					$output->writeln('<info>Base was successfully saved</info>');
					
					return 0;
				}
				
				return 1;
			}
			
		}
	}