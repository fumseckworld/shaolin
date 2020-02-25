<?php


namespace Eywa\Console\Cache {


    use Eywa\Cache\ApcuCache;
    use Eywa\Cache\FileCache;
    use Eywa\Cache\MemcacheCache;
    use Eywa\Cache\RedisCache;
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
            $this->setDescription("Clean all cache systems")->addArgument('system', InputArgument::OPTIONAL, 'The cahe system name to clear')->addArgument('mode',InputArgument::OPTIONAL,'The clear mode')->addArgument('interval',InputArgument::OPTIONAL,'The clear interval for watch mode');;
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            $x =  $input->getArgument('system');

            $mode =  $input->getArgument('mode');

            $time = def($input->getArgument('interval')) ? intval($input->getArgument('interval')) : 20;

           if (is_null($x))
           {

               if ((new FileCache())->clear())
                   $io->success('The file cache has been removed sucessfully');
               else
                   $io->error('The file cache has not been removed');

               if ((new RedisCache())->clear())
                   $io->success('The redis cache has been removed sucessfully');
               else
                   $io->error('The redis cache has not been removed');

               if ((new ApcuCache())->clear())
                   $io->success('The apcu cache has been removed sucessfully');
               else
                   $io->error('The apcu cache has not been removed');

               $io->success('All cache systems are now empty');
               return 0;
           }
            if (def($x))
            {
                if (def($mode) && $mode === 'watch')
                {
                    $io->title("Clearing the $x cache after every $time seconds of interval");

                    if ($x == 'all')
                    {

                        while (true)
                        {
                            sleep($time);

                            if ((new FileCache())->clear())
                                $io->success('The file cache has been removed sucessfully');
                            else
                                $io->error('The file cache has not been removed');

                            if ((new RedisCache())->clear())
                                $io->success('The redis cache has been removed sucessfully');
                            else
                                $io->error('The redis cache has not been removed');

                            if ((new ApcuCache())->clear())
                                $io->success('The apcu cache has been removed sucessfully');
                            else
                                $io->error('The apcu cache has not been removed');


                        }
                        $io->success('Cancel command has be found exit');
                        return 0;
                    }else
                    {
                        while (true)
                        {
                            sleep($time);

                            switch ($x)
                            {
                                case 'redis':
                                    if((new RedisCache())->clear())
                                        $io->success("The $x cache has been reset successfully");
                                break;
                                case 'file':
                                    if((new FileCache())->clear())
                                    $io->success("The $x cache has been reset successfully");
                                break;
                                case 'apcu':
                                    if((new ApcuCache())->clear())
                                        $io->success("The $x cache has been reset successfully");
                                break;
                                case 'memcache':
                                    if((new MemcacheCache())->clear())
                                        $io->success("The $x cache has been reset successfully");
                                break;
                                default:
                                    $io->error('The cache system used is not a valid system');
                                break;
                            }
                        }
                        $io->success('Cancel command has be found exit');
                        return 0;
                    }
                }else{


                    $io->title('We clearing the cache');
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
                }
                return  0;
            }

        }
    }
}