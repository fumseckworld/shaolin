<?php

declare(strict_types=1);

namespace Eywa\Database\Seed {


    use Symfony\Component\Console\Input\Input;
    use Symfony\Component\Console\Output\Output;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Seeding
    {


        /**
         *
         * List all migrations class sorted by generated date
         *
         * @return array
         */
        public static function list():array
        {
            $x = [];
            foreach (glob(base('db','Seeds','*.php')) as $seed)
            {
                $item = collect(explode(DIRECTORY_SEPARATOR,$seed))->last();


                $item = collect(explode('.',$item))->first();


                $class = '\Base\Seeds\\' .$item;

                $x[$item] = $class;
            }
            return $x;
        }

        /**
         *
         * Run the seeding
         *
         * @param Input|null $input
         * @param Output|null $output
         *
         * @return bool
         */
        public static function run(?Input $input = null,?Output $output = null): bool
        {
            $bool = collect();

            $io = new SymfonyStyle($input,$output);

            foreach (self::list() as $file => $class)
            {
                if (!is_null($output))
                {
                    $io->title("Started the $file seeding");
                }

                $bool->push(call_user_func([$class,'seed']));

                if ($bool->ok() && ! is_null($output))
                    $io->success("The $file seeding has been executed successfully");

            }
            return $bool->ok();

        }
    }
}