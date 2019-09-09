<?php
	
	namespace Imperium\Command;
	
	use Imperium\Exception\Kedavra;
    use Imperium\Model\Admin;
    use Imperium\Model\Web;
    use Symfony\Component\Console\Helper\Helper;
    use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Question\Question;
	
	class RemoveRoute extends \Symfony\Component\Console\Command\Command
	{
		protected static $defaultName = "route:destroy";
		
		private          $route_name;
		
		private          $id;
        /**
         * @var string
         */
        private $choose;
        /**
         * @var array
         */
        private $names;

        private $helper;

        protected function configure()
		{
			$this->setDescription('Delete a route');
		}

        public function name(bool $web = true)
        {
            $x = \collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->name);
                return $x->all();
            }

            foreach (Admin::all() as $v)
                $x->push($v->name);

            return $x->all();
        }
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
		 * @throws Kedavra
		 */
		public function interact(InputInterface $input, OutputInterface $output)
		{

			$this->helper = $this->getHelper('question');

            do {
                clear_terminal();

                $question = new Question("<info>Route for admin or web ?</info> : ");

                $question->setAutocompleterValues(['admin', 'web']);

                $this->choose = $this->helper->ask($input, $output, $question);

            } while (is_null($this->choose) || not_in(['admin', 'web'], $this->choose));


            $this->names =  $this->choose == 'web' ? $this->name() : $this->name(false);



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

            if (not_def($this->names))
            {
                clear_terminal();
                $output->writeln("<error>The table is empty</error>");
                return 1;
            }

            do
            {
                clear_terminal();

                $question = new Question("<info>Enter the route name</info> : ");

                $question->setAutocompleterValues($this->names);

                $x = $this->helper->ask($input, $output, $question);

                $this->route_name = $x;

            }while(is_null($x) || collect($this->names)->not_exist($x));

            $this->id =  $this->choose == 'web' ? Web::by('name',$this->route_name)->id : Admin::by('name',$this->route_name)->id;

		    if ($this->choose == 'web')
            {
                if(Web::destroy($this->id))
                {
                    $output->writeln('<bg=green;fg=white>The route was removed successfully</>');
                    return 0;
                }
            }else
            {
                if(Admin::destroy($this->id))
                {
                    $output->writeln('<bg=green;fg=white>The route was removed successfully</>');
                    return 0;
                }
            }

			$output->writeln('<bg=red;fg=white>Fail to remove route</>');
			return 1;
		}
		
	}