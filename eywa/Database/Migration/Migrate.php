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

            $return = collect();

            foreach (static::list($mode) as $class => $date)
            {
                $migration = static::file($class);

                $created_at = $class::$created_at;

                $down_success_message = $class::$down_success_message;

                $down_error_message = $class::$down_error_message;

                $up_success_message = $class::$up_success_message;

                $up_error_message = $class::$up_error_message;

                $up_title = $class::$up_title;

                $down_title = $class::$down_title;

                $x = new $class;

                equal($mode,'up') ?    $io->title($up_title) : $io->title($down_title);

                $return->push(call_user_func_array([$x, $mode], []));

                if ($return->ok())
                {
                    equal($mode,'up') ?    $io->success($up_success_message) : $io->success($down_success_message);
                }else
                {
                    equal($mode,'up') ?    $io->error($up_error_message) : $io->error($down_error_message);

                    return  1;
                }
                $return->clear();
            }
            equal($mode,'up') ? $io->success('All migrations has been excuted successfully') : $io->success('The rollback command has been executed successfully');

            return 0;
        }

        private static function file(string $class)
        {
            return collect(explode('\\',$class))->last();
        }
    }
}