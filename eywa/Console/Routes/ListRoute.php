<?php

namespace Eywa\Console\Routes {


    use DI\DependencyException;
    use DI\NotFoundException;

    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Admin;
    use Eywa\Http\Routing\Task;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Helper\TableStyle;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

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
         *
         * List routes
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            $table = new Table($output);
       
            $table
                ->setStyle('box')

                ->setHeaders(['id', 'name', 'url','controller','action','method'])
                ->setRows(
                    Web::all(\PDO::FETCH_ASSOC)
                )
            ;
            $table->render();

            return 0;
        }

    }
}