<?php
	
	namespace Imperium\Command;
	
	use Imperium\Exception\Kedavra;
	use Imperium\Model\Routes;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Question\Question;
	
	class RemoveRoute extends \Symfony\Component\Console\Command\Command
	{
		protected static $defaultName = "route:destroy";
		
		private          $route_name;
		
		private          $id;
		
		protected function configure()
		{
			$this->setDescription('Delete a route');
		}
		
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
		 * @throws Kedavra
		 */
		public function interact(InputInterface $input, OutputInterface $output)
		{
			$helper = $this->getHelper('question');
			$names = Routes::only('name');
			do
			{
				clear_terminal();
				$question = new Question("<info>Enter the route name</info> : ");
				$question->setAutocompleterValues($names);
				$x = $helper->ask($input, $output, $question);
				$this->route_name = $x;
			}while(is_null($x) || collect($names)->not_exist($x));
			$this->id = Routes::by('name', $this->route_name)->id;
		}
		
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
		 * @throws Kedavra
		 * @return int|void|null
		 */
		public function execute(InputInterface $input, OutputInterface $output)
		{
			if(Routes::destroy($this->id))
			{
				$output->writeln('<bg=green;fg=white>The route was removed successfully</>');
				return 0;
			}
			$output->writeln('<bg=red;fg=white>Fail to remove route</>');
			return 1;
		}
		
	}