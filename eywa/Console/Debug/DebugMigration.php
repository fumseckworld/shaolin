<?php

namespace Eywa\Console\Debug {


    use Exception;
    use Eywa\Database\Connexion\Connect;
    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class DebugMigration extends Command
    {
        protected static $defaultName = 'check:migrations';


        public function configure(): void
        {
            $this->setDescription('Check the migrations table');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws Exception
         *
         */
        public function execute(InputInterface $input, OutputInterface $output): int
        {
            $i = (new Table(ioc(Connect::class)))->from('migrations');

            $x = new \Symfony\Component\Console\Helper\Table($output);
            $x->setStyle('box')->setHeaders($i->columns()->all())->setRows($i->content(PDO::FETCH_ASSOC))->render();

            return 0;
        }
    }
}
