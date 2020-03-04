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

        protected function configure():void
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

            $x =  strval($input->getArgument('system'));


            $mode =  strval($input->getArgument('mode'));

            $time = def($input->getArgument('interval')) ? intval($input->getArgument('interval')) : 20;

           if (def($x))
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

               if ((new MemcacheCache())->clear())
                   $io->success('The memcache cache has been removed sucessfully');
               else
                   $io->error('The memcache cache has not been removed');

               $io->success('All cache systems are now empty');
               return 0;
           }
            if (def($x))
            {
                if (def($mode) && $mode === 'watch')
                {
                    $io->title(sprintf("Clearing the %s cache after every %d seconds of interval",$x,$time));

                    if ($x == 'all')
                    {

                        while (true)
                        {
                            sleep($time);

                            if ((new FileCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error('The file cache has not been removed');

                            if ((new RedisCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error('The redis cache has not been removed');

                            if ((new ApcuCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error('The apcu cache has not been removed');
                            if ((new MemcacheCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error('The memcache cache has not been removed');

                        }

                    }else
                    {
                        while (true)
                        {
                            sleep($time);

                            switch ($x)
                            {
                                case 'redis':
                                    if((new RedisCache())->clear())
                                        $io->success(sprintf('The %s cache has been reset successfully',$x));
                                    else
                                        $io->error(sprintf('The %s cache has not been reset',$x));
                                break;
                                case 'file':
                                    if((new FileCache())->clear())
                                        $io->success(sprintf('The %s cache has been reset successfully',$x));
                                    else
                                        $io->error(sprintf('The %s cache has not been reset',$x));
                                break;
                                case 'apcu':
                                    if((new ApcuCache())->clear())
                                        $io->success(sprintf('The %s cache has been reset successfully',$x));
                                    else
                                        $io->error(sprintf('The %s cache has not been reset',$x));
                                break;
                                case 'memcache':
                                    if((new MemcacheCache())->clear())
                                        $io->success(sprintf('The %s cache has been reset successfully',$x));
                                    else
                                        $io->error(sprintf('The %s cache has not been reset',$x));
                                break;
                                default:
                                    $io->error('The cache system used is not a valid system');
                                break;
                            }
                        }
                    }
                }else{

                    $io->title(sprintf("Clearing the %s cache",$x));
                    switch ($x)
                    {
                        case 'redis':
                            if((new RedisCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error(sprintf('The %s cache has not been reset',$x));
                        break;
                        case 'file':
                            if((new FileCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error(sprintf('The %s cache has not been reset',$x));
                        break;
                        case 'apcu':
                            if((new ApcuCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error(sprintf('The %s cache has not been reset',$x));
                        break;
                        case 'memcache':
                            if((new MemcacheCache())->clear())
                                $io->success(sprintf('The %s cache has been reset successfully',$x));
                            else
                                $io->error(sprintf('The %s cache has not been reset',$x));
                        break;
                        default:
                            $io->error('The cache system used is not a valid system');
                        break;
                    }
                }
                $io->success('All cache systems are now empty');
                return  0;
            }
            $io->error('command not work');
            return 1;
        }
    }
}