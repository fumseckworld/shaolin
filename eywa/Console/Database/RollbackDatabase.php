<?php


namespace Eywa\Console\Database {


    use Eywa\Database\Migration\Migrate;
    use Eywa\Exception\Kedavra;
    use ReflectionException;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class RollbackDatabase extends Command
    {
        protected static $defaultName = 'db:rollback';


        protected function configure():void
        {
            $this->setDescription('Rollback the migrations')
                ->setHelp('php shaolin db:migrate env');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws ReflectionException
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);
            return (new Migrate())->rollback($io);
        }
    }
}
