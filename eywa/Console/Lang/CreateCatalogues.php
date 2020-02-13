<?php


namespace Eywa\Console\Lang {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Console\Shell;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CreateCatalogues extends Command
    {
        protected static $defaultName = "catalogues:generate";


        protected function configure()
        {
            $this->setDescription('Create all catalogues');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws DependencyException
         * @throws Kedavra
         * @throws NotFoundException
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);

            $io->title('Create localization catalog');
            $x = collect();

            foreach (config('i18n','locales') as $locale)
            {
                if ($this->generate($locale))
                {
                    $io->title("Generating the $locale catalog");
                    $io->success("The $locale catalog was successfully generated");
                    $x->push(true);
                }else{
                    $io->warning("The $locale catalog already exist");
                    $x->push(false);
                }
            }
            if ($x->ok())
            {
                $io->success("All catalogs was successfully generated");
                return 0;
            }

            $io->error('Catalogs already exist');
            return  1;
        }

        /**
         * @param $locale
         * @return bool
         * @throws DependencyException
         * @throws NotFoundException
         */
        public function generate($locale)
        {
            if (!is_dir('po'))
                mkdir('po');
            if (!is_dir("po/$locale"))
            {
                $files = collect(glob(base('app','Views') .DIRECTORY_SEPARATOR.'*.php'))->merge(glob(base('app','Views').DIRECTORY_SEPARATOR. '*'.DIRECTORY_SEPARATOR.'*.php'))->join(' ');

                $app_name = env('APP_NAME','eywa');
                $app_version = env('APP_VERSION','1.0');
                $translator_email = env('TRANSLATOR_EMAIL','translator@free.fr');

                mkdir("po/$locale");
                mkdir("po/$locale/LC_MESSAGES");
                (new Shell("xgettext --keyword=_ --language=PHP --add-comments --msgid-bugs-address=$translator_email --package-version=$app_version  --package-name=$app_name --sort-output -o po/$app_name.pot -f $files"))->run();
                (new Shell("msginit --locale=$locale -i po/$app_name.pot -o po/$locale/LC_MESSAGES/messages.po"))->run();
                return true;
            }
            return false;

        }
    }
}