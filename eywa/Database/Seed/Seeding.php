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
                    $title = str_replace('%s',$class::$from,$class::$title);

                    $io->title($title);
                }

                $i = new $class;
                $bool->push(call_user_func_array([$i,'seed'],[]));

                if ($bool->ok() && ! is_null($output))
                {
                    $success_message = $class::$success_message;
                    $success_message = str_replace('%s',$class::$from,$success_message);
                    $success_message = str_replace('%d',$class::$generate,$success_message);

                    $io->success($success_message);
                }else{
                    $error_message = $class::$error_message;
                    $error_message = str_replace('%s',$class::$from,$error_message);
                    $error_message = str_replace('%d',$class::$generate,$error_message);
                    $io->error($error_message);
                    return false;
                }

            }
            return $bool->ok();

        }
    }
}