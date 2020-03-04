<?php

namespace Eywa\Console\Routes {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Web;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ListRoute extends Command
    {

        protected static $defaultName = "route:list";


        protected function configure():void
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

                ->setHeaders(['id', 'method', 'name','url','controller','action','namespace','created','updated'])
                ->setRows(
                    Web::all(PDO::FETCH_ASSOC)
                )
            ;
            $table->render();

            return 0;
        }

    }
}