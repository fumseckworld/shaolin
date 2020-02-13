<?php

namespace Eywa\Console\Routes {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Admin;
    use Eywa\Http\Routing\Task;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Helper\Helper;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;


    class RemoveRoute extends \Symfony\Component\Console\Command\Command
    {
        protected static $defaultName = "route:destroy";

        /**
         *
         * The route name
         *
         */
        private string $route_name;

        /**
         *
         * The route id
         *
         */
        private int $id = 0;

        /**
         *
         * The base choose
         *
         */
        private string $choose;

        /**
         *
         * The routes names
         *
         */
        private array $names;

        /**
         *
         * Instance of question helper
         *
         */
        private Helper $helper;

        protected function configure()
        {
            $this->setDescription('Delete a route');
        }

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws DependencyException
         * @throws Kedavra
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


            $this->choose = 'web';
            switch ($this->choose)
            {
                case 'admin':
                    $this->names = $this->name(false);
                break;
                case 'task':
                    $this->names = $this->name(false, false);
                break;
                default:
                    $this->names = $this->name();
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
            $io = new SymfonyStyle($input,$output);

            $io->title('Removing the route');
            if (not_def($this->names))
            {
                clear_terminal();
                $output->writeln("<error>The table is empty</error>");
                return 1;
            }

            do {
                clear_terminal();

                $question = new Question("<info>Enter the route name</info> : ");

                $question->setAutocompleterValues($this->names);

                $x = $this->helper->ask($input, $output, $question);

                $this->route_name = $x;

            } while (is_null($x) || collect($this->names)->not_exist($x));

            switch ($this->choose)
            {
                case 'admin':

                    $this->id = collect(collect(Admin::by('name', $this->route_name))->get(0))->first();
                    if (Admin::destroy($this->id))
                    {
                        $io->success('The route was removed successfully');
                        return 0;
                    }
                break;
                case 'task':
                    $this->id = intval(collect(Task::by('name', $this->route_name))->get('id'));
                    if (Task::destroy($this->id))
                    {
                        $io->success('The route was removed successfully');
                        return 0;
                    }
                break;
                default:
                    $this->id = collect(collect(Web::by('name', $this->route_name))->get(0))->first();

                    if (Web::destroy($this->id))
                    {
                        $io->success('The route was removed successfully');

                        return 0;
                    }
                    break;
            }
            $io->error('Fail to remove the route');
            return 1;
        }

    }
}