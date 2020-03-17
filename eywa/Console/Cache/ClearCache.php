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

        protected function configure():void
        {
            $this->setDescription("Clean all cache systems")->addArgument('system', InputArgument::OPTIONAL, 'The cahe system name to clear');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $x =  strval($input->getArgument('system'));


            if (def($x)) {
                if (not_in(['file','redis','apcu','memcache'], $x)) {
                    $io->error(sprintf('The %s system is not currently supported', $x));
                    return 1;
                }

                if (equal($x, 'file')) {
                    if ((new FileCache())->clear()) {
                        $io->success(sprintf('The %s cache has been removed sucessfully', $x));
                    } else {
                        $io->error(sprintf('The %s cache has not been removed', $x));
                        return 1;
                    }
                    return 0;
                }

                if (equal($x, 'redis')) {
                    if ((new RedisCache())->clear()) {
                        $io->success(sprintf('The %s cache has been removed sucessfully', $x));
                    } else {
                        $io->error(sprintf('The %s cache has not been removed', $x));
                        return 1;
                    }
                    return 0;
                }

                if (equal($x, 'apcu')) {
                    if ((new ApcuCache())->clear()) {
                        $io->success(sprintf('The %s cache has been removed sucessfully', $x));
                    } else {
                        $io->error(sprintf('The %s cache has not been removed', $x));
                        return 1;
                    }
                    return 0;
                }

                if (equal($x, 'memcache')) {
                    if ((new MemcacheCache())->clear()) {
                        $io->success(sprintf('The %s cache has been removed sucessfully', $x));
                    } else {
                        $io->error(sprintf('The %s cache has not been removed', $x));
                        return 1;
                    }
                    return 0;
                }
                return 0;
            } else {
                if ((new FileCache())->clear()) {
                    $io->success('The file cache has been reset successfully');
                } else {
                    $io->error('The file cache has not been removed');
                }

                if ((new RedisCache())->clear()) {
                    $io->success('The redis cache has been reset successfully');
                } else {
                    $io->error('The redis cache has not been removed');
                }

                if ((new ApcuCache())->clear()) {
                    $io->success('The apcu cache has been reset successfully');
                } else {
                    $io->error('The apcu cache has not been removed');
                }
                if ((new MemcacheCache())->clear()) {
                    $io->success('The memcache cache has been reset successfully');
                } else {
                    $io->error('The memcache cache has not been removed');
                }


                return 0;
            }
        }
    }
}
