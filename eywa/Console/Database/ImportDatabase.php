<?php

namespace Eywa\Console\Database {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Database\Connection\Connect;
    use Eywa\Database\Import\Import;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ImportDatabase extends Command
    {

        protected static $defaultName = 'db:import';

        /**
         * @throws Kedavra
         */
        protected function configure()
        {

            $base = db('base');
            $this->setAliases(['import']);
            $this->setDescription("Import sql file content into the $base database");
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            if ((new Import(ioc(Connect::class)))->import())
            {
                $output->writeln('<info>Sql file was imported successfully</info>');

                return 0;
            }

            return 1;
        }

    }
}