<?php
    
namespace Eywa\Console\Database
{

    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class DropTable extends Command
    {
        protected static $defaultName = 'table:drop';
            
        protected function configure(): void
        {
            $this->setDescription("Drop a table")
            ->addArgument('table', InputArgument::REQUIRED, 'The table name')
            ->addArgument('env', InputArgument::REQUIRED, 'The base environment');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $env = strval($input->getArgument('env'));

            not_in(['dev','prod','any'], $env, true, "Only dev, prod or any must be used");

            $io = new SymfonyStyle($input, $output);

            $x = strval($input->getArgument('table'));

            if (equal($env, 'dev')) {
                $table = new Table(development(), $x);

                if (not_def($table->show()->all())) {
                    $io->error('No tables has been found');
                    return 1;
                }

                if (is_false($table->exist())) {
                    $io->error(sprintf('The %s table not exist', $x));
                    return 1;
                }
                if ($table->drop()) {
                    $io->success(sprintf('The %s table has been removed successfully', $x));
                    return 0;
                } else {
                    $io->error(sprintf('Failed to remove the %s table', $x));
                    return 1;
                }
            }

            if (equal($env, 'prod')) {
                $table = new Table(production(), $x);

                if (not_def($table->show()->all())) {
                    $io->error('No tables has been found');
                    return 1;
                }

                if (is_false($table->exist())) {
                    $io->error(sprintf('The %s table not exist', $x));
                    return 1;
                }
                if ($table->drop()) {
                    $io->success(sprintf('The %s table has been removed successfully', $x));
                    return 0;
                } else {
                    $io->error(sprintf('Failed to remove the %s table', $x));
                    return 1;
                }
            }

            if (equal($env, 'any')) {
                $table = new Table(development(), $x);

                if (not_def($table->show()->all())) {
                    $io->error('No tables has been found');
                    return 1;
                }

                if (is_false($table->exist())) {
                    $io->error(sprintf('The %s table not exist', $x));
                    return 1;
                }
                if ($table->drop()) {
                    $io->success(sprintf('The %s table has been removed successfully', $x));
                } else {
                    $io->error(sprintf('Failed to remove the %s table', $x));
                    return 1;
                }

                $table = new Table(production(), $x);

                if (not_def($table->show()->all())) {
                    $io->error('No tables has been found');
                    return 1;
                }

                if (is_false($table->exist())) {
                    $io->error(sprintf('The %s table not exist', $x));
                    return 1;
                }
                if ($table->drop()) {
                    $io->success(sprintf('The %s table has been removed successfully', $x));
                } else {
                    $io->error(sprintf('Failed to remove the %s table', $x));
                    return 1;
                }
                $io->success(
                    sprintf(
                        'The %s table has been deleted of the production and the development database',
                        $x
                    )
                );
            }

            $io->error("Environment is not valid");
            return 1;
        }
    }
}
