<?php


namespace Eywa\Database\Migration {


    use Eywa\Exception\Kedavra;

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
            foreach (glob(base('app','Database','Migrations','*.php')) as $k => $v)
            {
                $item = collect(explode(DIRECTORY_SEPARATOR,$v))->last();


                $item = collect(explode('.',$item))->first();


                $class = '\App\Database\Migrations\\' .$item;

                $x[$class] = $class::$generared_at;
            }
            return $mode === 'up' ?  collect($x)->asort()->all() : collect($x)->arsort()->all();
        }

        /**
         *
         * Execute the migrations
         *
         * @param string $mode
         *
         * @return bool
         *
         * @throws Kedavra
         *
         */
        public static function run(string $mode):bool
        {
            not_in(['up','down'],$mode,true,"The mode must be is up or down");
            $x = collect();
            foreach (static::list($mode) as $class => $date)
            {
                $x->push(call_user_func([$class,$mode]));
            }
            return $x->ok();
        }
    }
}