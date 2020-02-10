<?php


namespace Eywa\Console\Lang {


    use DI\DependencyException;
    use DI\NotFoundException;
    use Eywa\Exception\Kedavra;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

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
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
           collect(config('i18n','locales'))->for([$this,'generate'])->ok();
           return 0;
        }

        /**
         * @param $locale
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

                system("xgettext --keyword=_ --language=PHP --add-comments --sort-output -o po/$app_name.pot -f $files");
              //  system("xgettext -k_ -j  -o po/$app_name.pot $files --package-name=$app_name --msgid-bugs-address=$translator_email --package-version=$app_version --language=PHP");
                system("msginit --locale=$locale -i po/$app_name.pot -o po/$locale/LC_MESSAGES/messages.po");
            }

        }
    }
}