<?php

namespace Eywa\Console\Routes {


    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Web;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Helper\TableSeparator;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ListRoute extends Command
    {
        protected static $defaultName = "route:list";


        protected function configure(): void
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

            $table ->setStyle('box')
                ->setHeaders(['id', 'metho', 'name','url','controller','namespace','action','created','updated'])
                ->setRows((new Sql(connect(SQLITE, base('routes', 'web.sqlite3')), 'routes'))->to(PDO::FETCH_ASSOC))
                    ->render();

            return 0;
        }
    }
}
