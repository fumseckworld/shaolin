<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Collection\Collect;
		use Imperium\Exception\Kedavra;
		use Imperium\Routing\Route;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		use Symfony\Component\Console\Question\Question;
		use Symfony\Component\Console\Terminal;
		
		class FindRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:find";
			
			/**
			 * @var Collect
			 *
			 */
			private $routes;
			
			/**
			 * @var Collect
			 */
			private $entry;
			
			private $search;
			
			protected function configure()
			{
				
				$this->setDescription('Find a route');
			}
			
			/**
			 *
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 *
			 */
			public function interact(InputInterface $input, OutputInterface $output)
			{
				
				$helper = $this->getHelper('question');
			
			
				do
				{
					
					do
					{
						clear_terminal();
						$question = new Question("<info>Please enter the search value : </info>");
						$question->setAutocompleterValues(Route::manage()->all());
						$this->search = $helper->ask($input, $output, $question);
						
					} while ( is_null($this->search) );
					clear_terminal();
				
					Route::manage()->list($input, $output, Route::manage()->find($this->search));
					
					$question = new Question("<info>Continue [Y/n] : </info>", 'Y');
					
					$continue = strtoupper($helper->ask($input, $output, $question));
					
					$continue = $continue === 'Y';
					
				} while ( $continue );
				
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @return int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
				
				$output->writeln('<info>bye</info>');
				
				return 0;
			}
			
		}
	}