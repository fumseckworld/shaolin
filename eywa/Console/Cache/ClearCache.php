<?php


namespace Eywa\Console\Cache {


    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\FileCache;
    use Eywa\Cache\MemcacheCache;
    use Eywa\Cache\RedisCache;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ClearCache extends Command
    {
        protected static $defaultName = 'cache:clear';

        protected function configure()
        {
            $this->setDescription("Clean all cache systems")->addArgument('system', InputArgument::OPTIONAL, 'The cahe system name to clear');;
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            $io->title('We clearing the cache');
            $x =  $input->getArgument('system');
            if (def($x))
            {
                switch ($x)
                {
                    case 'redis':
                        (new RedisCache())->clear();
                        $io->success("The $x cache has been reset successfully");
                        return 0;
                    break;
                    case 'file':
                        (new FileCache())->clear();
                        $io->success("The $x cache has been reset successfully");
                        return 0;
                    break;
                    case 'apcu':
                        (new ApcuCache())->clear();
                        $io->success("The $x cache has been reset successfully");
                        return 0;
                    break;
                    case 'memcache':
                        (new MemcacheCache())->clear();
                        $io->success("The $x cache has been reset successfully");
                        return 0;
                    break;
                    default:
                        $io->error('The cache system used is not a valid system');
                    break;
                }
            }else{
                if ((new FileCache())->clear() && (new ApcuCache())->clear() && (new RedisCache())->clear() && (new MemcacheCache())->clear())
                {
                    $io->success("All cache systems has been reset successfully");

                    return 0;
                }
                $io->error("Clear cache has been fail");

                return 1;
            }
            return  1;
        }
    }
}