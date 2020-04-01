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
        protected function configure(): void
        {
            $base = app()->connexion()->base();
            $this->setDescription(sprintf('Export the %s base content into a sql file', $base));
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
            $io = new SymfonyStyle($input, $output);
            if ((new Export(app()->connexion()))->dump()) {
                $io->success(sprintf('The %s base has been exported successfully', app()->connexion()->base()));
                return 0;
            }

            $io->error('Exportation has failed');
            return 1;
        }
    }
}
