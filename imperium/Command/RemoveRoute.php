<?php
	
	namespace Imperium\Command;
	
	use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Exception\Kedavra;
    use Imperium\Model\Admin;
    use Imperium\Model\Task;
    use Imperium\Model\Web;
    use Symfony\Component\Console\Helper\Helper;
    use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	use Symfony\Component\Console\Question\Question;
    use function collect;

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

        /**
         * @var Helper
         */
        private $helper;

        protected function configure()
		{
			$this->setDescription('Delete a route');
		}

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function name(bool $web = true, $admin = false)
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->name);
                return $x->all();
            }
            if ($admin)
            {
                foreach (Admin::all() as $v)
                    $x->push($v->name);

                return $x->all();
            }
            foreach (Task::all() as $v)
                $x->push($v->name);

            return $x->all();
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
		public function interact(InputInterface $input, OutputInterface $output)
		{

			$this->helper = $this->getHelper('question');

            do {
                clear_terminal();

                $question = new Question("<info>Route for admin, web or task ?</info> : ");

                $question->setAutocompleterValues(['admin', 'web','task']);

                $this->choose = $this->helper->ask($input, $output, $question);

            } while (is_null($this->choose) || not_in(['admin', 'web','task'], $this->choose));

            switch ($this->choose)
            {
                case 'admin':
                  $this->names =   $this->name(false);
                break;
                case 'task':
                    $this->names =  $this->name(false,false);
                break;
                default:
                    $this->names =   $this->name();
                break;
            }




		}

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|void|null
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
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

            switch ($this->choose)
            {
                case 'admin':
                  $this->id =   Admin::by('name',$this->route_name)->id;
                    if(Admin::destroy($this->id))
                    {
                        $output->writeln('<info>The route was removed successfully</info>');
                        return 0;
                    }
                break;
                case 'task':
                   $this->id = Task::by('name',$this->route_name)->id;
                    if(Task::destroy($this->id))
                    {
                        $output->writeln('<info>The route was removed successfully</info>');
                        return 0;
                    }
                break;
                default:
                   $this->id =  Web::by('name',$this->route_name)->id;
                    if(Web::destroy($this->id))
                    {
                        $output->writeln('<info>The route was removed successfully</info>');
                        return 0;
                    }
                break;
            }

			$output->writeln('<bg=red;fg=white>Fail to remove route</>');
			return 1;
		}
		
	}