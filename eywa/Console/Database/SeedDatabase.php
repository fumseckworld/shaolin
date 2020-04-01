<?php
    
namespace Eywa\Console\Database
{

    use Eywa\Database\Seed\Seeding;
    use ReflectionException;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class SeedDatabase extends Command
    {
        protected static $defaultName = 'db:seed';
            
        protected function configure(): void
        {
            $this->setDescription("Seed the development database");
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws ReflectionException
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            return Seeding::run($io);
        }
    }
}
