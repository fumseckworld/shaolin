<?php

namespace Imperium\Command {

    use Imperium\Exception\Kedavra;
    use Imperium\Model\Admin;
    use Imperium\Model\Web;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class FindRoute extends \Symfony\Component\Console\Command\Command
    {

        protected static $defaultName = "route:find";

        private $search;
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
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {

            $helper = $this->getHelper('question');

            do {

                do {
                    clear_terminal();

                    $question = new Question("<info>Route for admin or web ?</info> : ");

                    $question->setAutocompleterValues(['admin', 'web']);

                    $this->choose = $helper->ask($input, $output, $question);

                } while (is_null($this->choose) || not_in(['admin', 'web'], $this->choose));

                do {
                    clear_terminal();

                    $question = new Question("<info>Please enter the search value : </info>");

                    if (equal($this->choose,'web'))
                        $question->setAutocompleterValues($this->web());
                    else
                        $question->setAutocompleterValues($this->admin());

                    $this->search = $helper->ask($input, $output, $question);

                } while (is_null($this->search));

                clear_terminal();


                $this->choose == 'admin' ? routes($output, Admin::search($this->search)) : routes($output, Web::search($this->search));

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

        private function controller(bool $web = true): array
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->controller);
                return $x->all();
            }

            foreach (Admin::all() as $v)
                $x->push($v->controller);

            return $x->all();
        }

        private function name(bool $web = true): array
        {
            $x = collect();
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

        private function url(bool $web = true): array
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->url);
                return $x->all();
            }

            foreach (Admin::all() as $v)
                $x->push($v->url);

            return $x->all();
        }

        private function action(bool $web =true)
        {
            $x = collect();
            if ($web)
            {
                foreach (Web::all() as $v)
                    $x->push($v->action);
                return $x->all();
            }

            foreach (Admin::all() as $v)
                $x->push($v->action);

            return $x->all();
        }

        /**
         * @return array
         */
        private function web()
        {

            return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(),$this->name(), $this->url(),$this->action(), $this->controller())->all();
        }
        /**
         * @return array
         */
        private function admin()
        {

            return collect()->merge(controllers(), collect(METHOD_SUPPORTED)->for('strtolower')->all(),$this->name(false),$this->url(false),$this->action(false),$this->controller(false))->all();
        }

    }
}