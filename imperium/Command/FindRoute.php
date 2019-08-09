<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Exception\Kedavra;
		use Imperium\Model\Routes;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		use Symfony\Component\Console\Question\Question;
		
		class FindRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:find";
			
			private $search;
			
			protected function configure()
			{
				
				$this->setDescription('Find a route');
			}
			
			/**
			 * @throws Kedavra
			 * @return array
			 */
			private function all()
			{
				
				return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(), Routes::only('name'), Routes::only('url'), Routes::only('action'), Routes::only('controller'))->all();
			}
			
			/**
			 *
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 *
			 * @throws Kedavra
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
						$question->setAutocompleterValues($this->all());
						$this->search = $helper->ask($input, $output, $question);
						
					}while(is_null($this->search));
					clear_terminal();
					
					routes($output, Routes::search($this->search));
					
					$question = new Question("<info>Continue [Y/n] : </info>", 'Y');
					
					$continue = strtoupper($helper->ask($input, $output, $question));
					
					$continue = $continue === 'Y';
					
				}while($continue);
				
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