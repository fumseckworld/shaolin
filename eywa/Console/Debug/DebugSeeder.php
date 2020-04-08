<?php

namespace Eywa\Console\Debug {


    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class DebugSeeder extends Command
    {
        protected static $defaultName = 'check:seeds';


        public function configure(): void
        {
            $this->setDescription('Check the seeder table content')
            ->addArgument('table', InputArgument::REQUIRED, 'The table name');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         *
         */
        public function execute(InputInterface $input, OutputInterface $output): int
        {
            $i = (new Table(development()))->from(strval($input->getArgument('table')));

            $x = new \Symfony\Component\Console\Helper\Table($output);
            $x->setStyle('box')->setHeaders($i->columns()->all())->setRows($i->content(PDO::FETCH_ASSOC))->render();

            return 0;
        }
    }
}
