<?php


namespace Eywa\Database\Migration {


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
            not_in(['up','down'],$mode,true,"The mode must be is up or down");

            return  equal($mode,'up') ? static::migrate($io,$mode) :  static::rollback($io,$mode);
        }

        private static function file(string $class)
        {
            return collect(explode('\\',$class))->last();
        }

        /**
         * @param SymfonyStyle $io
         * @param string $mode
         * @return int
         * @throws Kedavra
         */
        private static function migrate(SymfonyStyle $io,string $mode = 'up'): int
        {

            $return = collect();
            $all = collect(static::list($mode));
            $sql = sql('migrations');

            if (def($sql->execute()))
            {
                foreach ($all->all() as $class => $date)
                {

                    $migration = static::file($class);

                    $created_at = $class::$created_at;

                    $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                    $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                    $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                    $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                    $up_title = str_replace('%s',$class::$table,$class::$up_title);

                    $down_title = str_replace('%s',$class::$table,$class::$down_title);

                    $exist = $sql->where('version',EQUAL,$date)->exist() && $sql->where('migration',EQUAL,$migration)->exist();


                    if (!$exist)
                    {
                        $x = new $class;

                        $io->title($up_title);

                        $return->push(call_user_func_array([$x, $mode], []));

                        if ($return->ok())
                        {
                            $io->success($up_success_message);
                            $sql->save(['version'=> $created_at,'migration'=> $migration,'time'=> now()->toDateTimeString()]);
                            return  0;
                        }

                        $sql->where('version',EQUAL,$date)->destroy();
                        $io->error($up_error_message);
                        return 1;
                    }

                }
            }else
            {
                foreach ($all->all() as $class => $date)
                {

                    $migration = static::file($class);


                    $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                    $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                    $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                    $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                    $up_title = str_replace('%s',$class::$table,$class::$up_title);

                    $down_title = str_replace('%s',$class::$table,$class::$down_title);

                    $exist = $sql->where('version',EQUAL,$date)->exist();

                    if (!$exist)
                    {
                        $x = new $class;
                        $io->title($up_title);
                        $return->push(call_user_func_array([$x, $mode], []));

                        if ($return->ok())
                        {
                            $io->success($up_success_message);
                            $sql->save(['version'=> $date,'migration'=> $migration,'time'=> now()->toDateTimeString()]);
                            return  0;
                        }
                        $io->error($up_error_message);
                        return 1;
                    }
                }
            }

            return 0;
        }

        /**
         * @param SymfonyStyle $io
         * @param string $mode
         * @return int
         * @throws Kedavra
         */
        private static function rollback(SymfonyStyle $io,string $mode = 'down'): int
        {

            $return = collect();
            $all = collect(static::list($mode));
            $sql = sql('migrations');

            if (def($sql->execute()))
            {
                foreach ($all->all() as $class => $date)
                {

                    $migration = static::file($class);

                    $created_at = $class::$created_at;


                    $down_success_message = str_replace('%s',$class::$table,$class::$down_success_message);

                    $down_error_message = str_replace('%s',$class::$table,$class::$down_error_message);

                    $up_success_message = str_replace('%s',$class::$table,$class::$up_success_message);

                    $up_error_message = str_replace('%s',$class::$table,$class::$up_error_message);

                    $up_title = str_replace('%s',$class::$table,$class::$up_title);

                    $down_title = str_replace('%s',$class::$table,$class::$down_title);

                    $exist = $sql->where('version',EQUAL,$date)->exist() && $sql->where('migration',EQUAL,$migration)->exist();


                    if ($exist)
                    {
                        $x = new $class;

                        $io->title($down_title);

                        $return->push(call_user_func_array([$x, $mode], []));

                        if ($return->ok())
                        {
                            $io->success($down_success_message);
                            $sql->where('version',EQUAL,$date)->destroy();
                            return  0;
                        }
                        $io->error($down_error_message);
                        return 1;
                    }

                }
            }

              return 0;
        }

        /**
         * @return bool
         * @throws Kedavra
         */
        public static function check_migrate(): bool
        {
            $sql = sql('migrations');
            $return = collect();
            foreach (static::list('up') as $class => $date)
            {
                $return->push($sql->where('version',EQUAL,$date)->exist());
            }
            return $return->ok();
        }


        /**
         * @return bool
         * @throws Kedavra
         */
        public static function check_rollback(): bool
        {
            return not_def(sql('migrations')->execute());

        }
    }
}