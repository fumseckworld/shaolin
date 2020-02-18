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
         * @return array
         */
        public static function list(string $mode = 'up'): array
        {
            $x = [];
            foreach (glob(base('db','Migrations','*.php')) as $k => $v)
            {
                $item = collect(explode(DIRECTORY_SEPARATOR,$v))->last();


                $item = collect(explode('.',$item))->first();


                $class = '\Base\Migrations\\' .$item;

                $x[$class] = $class::$created_at;
            }
            return $mode === 'up' ?  collect($x)->asort()->all() : collect($x)->arsort()->all();
        }

        /**
         *
         * Execute the migrations
         *
         * @param string $mode
         *
         * @param SymfonyStyle $io
         * @return int
         *
         * @throws Kedavra
         */
        public static function run(string $mode,SymfonyStyle $io):int
        {
            return  equal($mode,'up') ? static::migrate($io) :  static::rollback($io);
        }

        private static function file(string $class)
        {
            return collect(explode('\\',$class))->last();
        }

        /**
         * @param SymfonyStyle $io
         * @return int
         * @throws Kedavra
         */
        private static function migrate(SymfonyStyle $io): int
        {
            static::do('dev','up',$io) ;
            static::do('prod','up',$io);
            return 0;
        }

        /**
         * @param SymfonyStyle $io
         * @return int
         * @throws Kedavra
         */
        private static function rollback(SymfonyStyle $io): int
        {
            static::do('dev','down',$io);
            static::do('prod','down',$io);
            return 0;
        }

        /**
         * @return bool
         * @throws Kedavra
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
         * @param string $mode
         * @param string $table
         * @return Sql
         * @throws Kedavra
         */
        private static function sql(string $mode,string $table = 'migrations'): Sql
        {
            return equal($mode,'dev') ? new Sql(development(),$table) : new Sql(production(), $table);
        }

        /**
         * @return bool
         * @throws Kedavra
         */
        public static function check_rollback(): bool
        {
            return not_def(static::sql('dev')->execute()) && not_def(static::sql('prod')->execute());

        }

        /**
         * @param string $env
         * @param string $mode
         * @param SymfonyStyle $io
         * @return bool|int
         * @throws Kedavra
         */
        private static function do(string $env,string $mode,SymfonyStyle $io)
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

                        $migration = static::file($class);

                        $created_at = $class::$created_at;

                        $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                        $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                        $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                        $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                        $up_title = str_replace('%s',$class::$table,$class::$up_title);

                        $down_title = str_replace('%s',$class::$table,$class::$down_title);

                        $exist = $sql->where('version',EQUAL,$date)->exist();

                        if (!$exist)
                        {
                            $x = equal($env,'prod') ?  new $class(production(),$mode,$env) : new $class(development(),$mode,$env);

                            $io->title("$up_title ($env)");

                            $result->push(call_user_func_array([$x, $mode], []));

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

                        $migration = static::file($class);

                        $created_at = $class::$created_at;

                        $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                        $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                        $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                        $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                        $up_title = str_replace('%s',$class::$table,$class::$up_title);

                        $down_title = str_replace('%s',$class::$table,$class::$down_title);

                        $exist = $sql->where('version',EQUAL,$date)->exist();

                        if ($exist)
                        {
                            $x = equal($env,'prod') ?  new $class(production(),$mode,$env) : new $class(development(),$mode,$env);

                            $io->title("$down_title ($env)");

                            $result->push(call_user_func_array([$x, $mode], []));

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

                    $migration = static::file($class);

                    $created_at = $class::$created_at;

                    $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                    $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                    $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                    $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                    $up_title = str_replace('%s',$class::$table,$class::$up_title);

                    $down_title = str_replace('%s',$class::$table,$class::$down_title);

                    $exist = $sql->where('version',EQUAL,$date)->exist();

                    if (!$exist)
                    {
                        $x = equal($env,'prod') ?  new $class(production(),$mode,$env) : new $class(development(),$mode,$env);

                        $io->title("$up_title ($env)");

                        $result->push(call_user_func_array([$x, $mode], []));

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

                    $migration = static::file($class);

                    $created_at = $class::$created_at;

                    $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                    $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                    $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                    $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                    $up_title = str_replace('%s',$class::$table,$class::$up_title);

                    $down_title = str_replace('%s',$class::$table,$class::$down_title);

                    $exist = $sql->where('version',EQUAL,$date)->exist();

                    if ($exist)
                    {
                        $x = equal($env,'prod') ?  new $class(production(),$mode,$env) : new $class(development(),$mode,$env);

                        $io->title("$down_title ($env)");

                        $result->push(call_user_func_array([$x, $mode], []));

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