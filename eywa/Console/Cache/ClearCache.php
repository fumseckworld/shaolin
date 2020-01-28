<?php


namespace Eywa\Console\Cache {


    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\Filecache;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ClearCache extends Command
    {
        protected static $defaultName = 'cache:clear';

        protected function configure()
        {
            $this->setDescription("Clean all cache systems");
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            if ((new Filecache())->clear() && (new ApcuCache())->clear())
            {
                $output->writeln("<info>Cache has been reset successfully</info>");

                return 0;
            }
            $output->writeln("<error>Clear cache has been fail</error>");
            return 1;
        }
    }
}