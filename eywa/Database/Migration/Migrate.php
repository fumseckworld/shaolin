<?php


namespace Eywa\Database\Migration {


    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Migrate
    {

        /**
         *
         * List all migrations class sorted by generated date
         *
         * @param string $mode
         *
         * @return array<string>
         *
         * @throws \ReflectionException
         */
        public static function list(string $mode = 'up'): array
        {
            $x = [];

            foreach (files(base('db','Migrations','*.php')) as $k => $v)
            {
                $item = collect(explode(DIRECTORY_SEPARATOR,$v))->last();


                $item = collect(explode('.',$item))->first();


                $class = '\Base\Migrations\\' .$item;

                $x[$class] = (new \ReflectionClass(new $class))->getStaticPropertyValue('created_at');
            }
            return $mode === 'up' ?  collect($x)->asort()->all() : collect($x)->arsort()->all();
        }

        /**
         *
         * Execute the migrations
         *
         * @param string $mode
         * @param SymfonyStyle $io
         *
         * @return int
         *
         * @throws Kedavra
         * @throws \ReflectionException
         *
         */
        public static function run(string $mode,SymfonyStyle $io):int
        {
            return  equal($mode,'up') ? static::migrate($io) :  static::rollback($io);
        }

        /**
         *
         * Get the class name
         *
         * @param string $class
         *
         * @return string
         *
         */
        private static function file(string $class): string
        {
            return collect(explode('\\',$class))->last();
        }

        /**
         * @param SymfonyStyle $io
         * @return int
         * @throws Kedavra
         * @throws \ReflectionException
         */
        private static function migrate(SymfonyStyle $io): int
        {
            static::do('dev','up',$io) ;
            static::do('prod','up',$io);
            return 0;
        }

        /**
         *
         * Rollback the database
         *
         * @param SymfonyStyle $io
         *
         * @return int
         *
         * @throws Kedavra
         * @throws \ReflectionException
         */
        private static function rollback(SymfonyStyle $io): int
        {
            static::do('dev','down',$io);
            static::do('prod','down',$io);
            return 0;
        }

        /**
         *
         * Check if new migrations are available
         *
         * @return bool
         *
         * @throws Kedavra
         * @throws \ReflectionException
         *
         */
        public static function check_migrate(): bool
        {
            $prod = static::sql('prod');
            $dev =  static::sql('dev');
            $return = collect();
            foreach (static::list('up') as $class => $date)
            {
                $return->push($prod->where('version',EQUAL,$date)->exist() && $dev->where('version',EQUAL,$date)->exist());
            }
            return $return->ok();
        }


        /**
         *
         * Get an instance of the queri builder
         *
         * @param string $mode
         * @param string $table
         *
         * @return Sql
         *
         * @throws Kedavra
         *
         */
        private static function sql(string $mode,string $table = 'migrations'): Sql
        {
            return equal($mode,'dev') ? new Sql(development(),$table) : new Sql(production(), $table);
        }

        /**
         *
         * Check if migrations has been executed
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function check_rollback(): bool
        {
            return not_def(static::sql('dev')->execute()) && not_def(static::sql('prod')->execute());

        }

        /**
         *
         * Execute the command
         *
         * @param string $env
         * @param string $mode
         * @param SymfonyStyle $io
         *
         * @return int
         *
         * @throws Kedavra
         * @throws \ReflectionException
         *
         */
        private static function do(string $env,string $mode,SymfonyStyle $io): int
        {

            not_in(['up','down'],$mode,true,"Mode must be up or down");
            not_in(['dev','prod'],$env,true,"Env must be dev or prod");

            $sql = static::sql($env);

            $all = static::list($mode);

            $result = collect();

            if (def($sql->execute()))
            {
                if (equal($mode,'up'))
                {
                    foreach ($all as $class => $date)
                    {

                        $x = new \ReflectionClass(new $class);

                        $table = $x->getStaticPropertyValue('table');

                        $migration = static::file($class);

                        $created_at = $x->getStaticPropertyValue('created_at');

                        $up_success_message = str_replace('%s',$table,$x->getStaticPropertyValue('up_success_message'));

                        $up_error_message = str_replace('%s',$table,$x->getStaticPropertyValue('up_error_message'));

                        $up_title = str_replace('%s',$table,$x->getStaticPropertyValue('up_title'));


                        $exist = $sql->where('version',EQUAL,$date)->exist();

                        if (!$exist)
                        {

                           $i = equal($env,'prod') ?   $x->newInstance(production(),$mode,$env) : $x->newInstance(development(),$mode,$env);

                            $io->title("$up_title ($env)");


                            $result->push($x->getMethod('up')->getClosure($i));


                            if ($result->ok())
                            {
                                $io->success($up_success_message);
                                $sql->save(['version'=> $created_at,'migration'=> $migration,'time'=> now()->toDateTimeString()]);
                                return  0;
                            }else
                            {

                                $sql->where('version',EQUAL,$date)->destroy();
                                $io->error($up_error_message);
                                return 1;
                            }

                        }
                    }
                }

                if (equal($mode,'down'))
                {
                    foreach ($all as $class => $date)
                    {


                        $x = new \ReflectionClass(new $class);

                        $table = $x->getStaticPropertyValue('table');

                        $down_success_message = str_replace('%s',$table,$x->getStaticPropertyValue('down_success_message'));

                        $down_error_message = str_replace('%s',$table,$x->getStaticPropertyValue('down_error_message'));

                        $down_title = str_replace('%s',$table,$x->getStaticPropertyValue('down_title'));

                        $exist = $sql->where('version',EQUAL,$date)->exist();


                        if ($exist)
                        {
                            $i = equal($env,'prod') ?   $x->newInstance(production(),$mode,$env) : $x->newInstance(development(),$mode,$env);

                            $io->title("$down_title ($env)");

                            $result->push($x->getMethod('up')->getClosure($i));

                            if ($result->ok())
                            {
                                $io->success($down_success_message);
                                $sql->where('version',EQUAL,$date)->destroy();

                                return  0;
                            }else
                            {
                                $io->error($down_error_message);
                                return 1;
                            }

                        }
                    }
                }

            }

            if (equal($mode,'up'))
            {
                foreach ($all as $class => $date)
                {

                    $x = new \ReflectionClass(new $class);

                    $table = $x->getStaticPropertyValue('table');

                    $migration = static::file($class);

                    $created_at = $x->getStaticPropertyValue('created_at');

                    $up_success_message = str_replace('%s',$table,$x->getStaticPropertyValue('up_success_message'));

                    $up_error_message = str_replace('%s',$table,$x->getStaticPropertyValue('up_error_message'));

                    $up_title = str_replace('%s',$table,$x->getStaticPropertyValue('up_title'));

                    $exist = $sql->where('version',EQUAL,$date)->exist();

                    if (!$exist)
                    {
                        $i= equal($env,'prod') ?   $x->newInstance(production(),$mode,$env) : $x->newInstance(development(),$mode,$env);

                        $io->title("$up_title ($env)");

                        $result->push($x->getMethod('up')->getClosure($i));

                        if ($result->ok())
                        {
                            $io->success($up_success_message);
                            $sql->save(['version'=> $created_at,'migration'=> $migration,'time'=> now()->toDateTimeString()]);
                            return  0;
                        }else
                        {

                            $sql->where('version',EQUAL,$date)->destroy();
                            $io->error($up_error_message);
                            return 1;
                        }

                    }
                }
            }

            if (equal($mode,'down'))
            {
                foreach ($all as $class => $date)
                {


                    $x = new \ReflectionClass(new $class);

                    $table = $x->getStaticPropertyValue('table');

                    $down_success_message = str_replace('%s',$table,$x->getStaticPropertyValue('down_success_message'));

                    $down_error_message = str_replace('%s',$table,$x->getStaticPropertyValue('down_error_message'));

                    $down_title = str_replace('%s',$table,$x->getStaticPropertyValue('down_title'));

                    $exist = $sql->where('version',EQUAL,$date)->exist();

                    if ($exist)
                    {
                        $i = equal($env,'prod') ?   $x->newInstance(production(),$mode,$env) : $x->newInstance(development(),$mode,$env);

                        $io->title("$down_title ($env)");

                        $result->push($x->getMethod('up')->getClosure($i));

                        if ($result->ok())
                        {
                            $io->success($down_success_message);
                            $sql->where('version',EQUAL,$date)->destroy();

                            return  0;
                        }else
                        {
                            $io->error($down_error_message);
                            return 1;
                        }

                    }
                }
            }
            return  0;

        }
    }
}