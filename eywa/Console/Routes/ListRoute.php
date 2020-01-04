<?php

namespace Eywa\Console\Routes {


    use DI\DependencyException;
    use DI\NotFoundException;

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Admin;
    use Eywa\Http\Routing\Task;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;

    class ListRoute extends Command
    {

        protected static $defaultName = "route:list";

        /**
         *
         * The base choose
         *
         */
        private string $choose;

        protected function configure()
        {

            $this->setDescription('List all routes');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $helper = $this->getHelper('question');
            do
            {
                clear_terminal();

                $question = new Question("<info>Route for admin, web or task ?</info> : ");

                $question->setAutocompleterValues(['admin', 'web', 'task']);

                $this->choose = $helper->ask($input, $output, $question);

            } while (is_null($this->choose) || not_in(['admin', 'web', 'task'], $this->choose));

        }

        /**
         *
         * List routes
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            switch ($this->choose)
            {
                case 'admin':
                    routes($output, Admin::all());
                break;
                case 'task':
                    routes($output, Task::all());
                break;
                default:
                    routes($output, Web::all());
                break;
            }
            return 0;
        }

    }
}