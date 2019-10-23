<?php

namespace Imperium\Command {

    use DI\DependencyException;
    use DI\NotFoundException;
    use Imperium\Exception\Kedavra;
    use Imperium\Model\Admin;
    use Imperium\Model\Task;
    use Imperium\Model\Web;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class FindRoute extends \Symfony\Component\Console\Command\Command
    {

        protected static $defaultName = "route:find";

        /**
         * @var string
         */
        private $search;
        /**
         * @var string
         */
        private $choose;
        
        protected function configure()
        {

            $this->setDescription('Find a route');
        }


        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         *
         * @throws Kedavra
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

            do {

                do {
                    clear_terminal();

                    $question = new Question("<info>Route for admin, web or task ?</info> : ");

                    $question->setAutocompleterValues(['admin', 'web','task']);

                    $this->choose = $helper->ask($input, $output, $question);

                } while (is_null($this->choose) || not_in(['admin', 'web','task'], $this->choose));

                do {
                    clear_terminal();

                    $question = new Question("<info>Please enter the search value : </info>");

                    switch ($this->choose)
                    {
                        case 'admin':
                            $question->setAutocompleterValues($this->admin());
                        break;
                        case 'task':
                            $question->setAutocompleterValues($this->task());
                        break;
                        default:
                            $question->setAutocompleterValues($this->web());
                        break;
                    }

                   $this->search = $helper->ask($input, $output, $question);

                } while (is_null($this->search));

                clear_terminal();

                switch ($this->choose)
                {
                    case 'admin':
                        routes($output,Admin::search($this->search)) ;
                    break;
                    case 'task':
                        routes($output, Task::search($this->search));
                    break;
                    default:
                        routes($output,Web::search($this->search));
                    break;
                }


                $question = new Question("<info>Continue [Y/n] : </info>", 'Y');

                $continue = strtoupper($helper->ask($input, $output, $question)) === 'Y';

            } while ($continue);

        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            $output->writeln('<info>bye</info>');

            return 0;
        }

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function controller(bool $web =true,bool $admin = false): array
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->controller);
                return $x->all();
            }

            if ($admin)
            {
                foreach (Admin::all() as $v)
                     $x->push($v->controller);


                return $x->all();
            }


            foreach (Task::all() as $v)
                $x->push($v->controller);


            return $x->all();

        }

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function name(bool $web = true,bool $admin =false): array
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
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function url(bool $web = true,bool $admin = false): array
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->url);
                return $x->all();
            }

            if ($admin)
            {
                foreach (Admin::all() as $v)
                    $x->push($v->url);
                return $x->all();
            }


            foreach (Task::all() as $v)
                $x->push($v->url);

            return $x->all();

        }

        /**
         * @param bool $web
         * @param bool $admin
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function action(bool $web =true,bool $admin = false)
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->action);
                return $x->all();
            }

            if ($admin)
            {
                foreach (Admin::all() as $v)
                    $x->push($v->action);

                return $x->all();
            }


            foreach (Task::all() as $v)
                $x->push($v->action);

            return $x->all();
        }

        /**
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function web()
        {

            return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(),$this->name(), $this->url(),$this->action(), $this->controller())->all();
        }

        /**
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function admin()
        {

            return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(),$this->name(false,true),$this->url(false,true),$this->action(false,true),$this->controller(false,true))->all();
        }

        /**
         * @return array
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        private function task()
        {

            return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(),$this->name(false,false),$this->url(false,false),$this->action(false,false),$this->controller(false,false))->all();
        }

    }
}