<?php

namespace Eywa\Console\Database {


    use Exception;
    use Eywa\Database\Import\Import;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ImportDatabase extends Command
    {
        protected static $defaultName = 'db:import';

        /**
         * @throws Kedavra
         * @throws Exception
         */
        protected function configure(): void
        {
            $base = app()->connexion()->base();
            $this->setDescription(sprintf('Import sql file content into the %s database', $base));
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws Kedavra
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            if ((new Import(app()->connexion()))->import()) {
                $io->success(sprintf('The %s base has been updated successfully', app()->connexion()->base()));
                return 0;
            }

            $file = app()->connexion()->base() . '.sql';

            $io->error(sprintf('The %s file not exist or authentication problems', $file));
            return 1;
        }
    }
}
