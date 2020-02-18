<?php

namespace Eywa\Console\Database {


    use Exception;
    use Eywa\Database\Export\Export;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ExportDatabase extends Command
    {

        protected static $defaultName = 'db:export';

        /**
         * @throws Kedavra
         * @throws Exception
         */
        protected function configure()
        {
            $base = app()->connexion()->base();
            $this->setDescription("Export the $base base content into a sql file");
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            $io = new SymfonyStyle($input,$output);
            if((new Export(app()->connexion()))->dump())
            {
                $io->success('The base was successfully saved');
                return 0;
            }

            $io->error('The export task has failed');
            return 1;
        }

    }
}