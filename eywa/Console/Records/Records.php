<?php


namespace Eywa\Console\Records {


    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class Records extends Command
    {
        protected static $defaultName = 'record:show';


        public function configure():void
        {
            $this->setDescription('List the content of a table')->addArgument('table',InputArgument::REQUIRED,'The table name')->addArgument('env',InputArgument::REQUIRED,'The env mode');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output):int
        {
            $env = strval($input->getArgument('env'));
            $table = strval($input->getArgument('table'));
            $connect = equal($env,'prod') ? production() : development();
            $i = new Table($connect,$table);

            $x = new \Symfony\Component\Console\Helper\Table($output);
            $x->setStyle('box')->setHeaders($i->columns()->all())->setRows($i->content(PDO::FETCH_ASSOC))->render();

            return 0;

        }

    }
}