<?php

declare(strict_types=1);

namespace Eywa\Database\Seed {


    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Seeding
    {

        /**
         *
         * List all migrations class sorted by generated date
         *
         * @return array<string>
         *
         */
        public static function list(): array
        {
            $x = [];
            foreach (files(base('db','Seeds','*.php')) as $seed)
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
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return bool
         *
         * @throws \ReflectionException
         *
         */
        public static function run(InputInterface $input,OutputInterface $output): bool
        {
            $bool = collect();

            $io = new SymfonyStyle($input,$output);

            foreach (self::list() as $file => $class)
            {

                    $x = new \ReflectionClass(new $class);

                    $title = str_replace('%s',$x->getStaticPropertyValue('from'),$x->getStaticPropertyValue('title'));

                    $io->title($title);


                    $bool->push($x->getMethod('seed')->getClosure($x->newInstance()));


                if ($bool->ok())
                {
                    $success_message =  $x->getStaticPropertyValue('success_message');
                    $success_message = str_replace('%s',$x->getStaticPropertyValue('from'),$success_message);
                    $success_message = str_replace('%d',$x->getStaticPropertyValue('generate'),$success_message);

                    $io->success($success_message);
                }else{
                    $error_message =  $x->getStaticPropertyValue('error_message');
                    $error_message = str_replace('%s',$x->getStaticPropertyValue('from'),$error_message);
                    $error_message = str_replace('%d',$x->getStaticPropertyValue('generate'),$error_message);

                    $io->error($error_message);
                    return false;
                }

            }
            return $bool->ok();

        }
    }
}