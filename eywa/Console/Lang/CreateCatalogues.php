<?php


namespace Eywa\Console\Lang {


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
        public function execute(InputInterface $input, OutputInterface $output)
        {
           collect(config('lang','locales'))->for([$this,'generate'])->ok();
           return 0;
        }

        public function generate($locale)
        {

            if (!is_dir('po'))
                mkdir('po');
            if (!is_dir("po/$locale"))
            {
                $files = collect(glob(base('app','views') .DIRECTORY_SEPARATOR.'*.php'))->merge(glob(base('app','views').DIRECTORY_SEPARATOR. '*'.DIRECTORY_SEPARATOR.'*.php'))->join(' ');
                $domain = config('lang','domain');
                $app_name = env('APP_NAME');
                $app_version = env('APP_VERSION');
                $translator_email = env('TRANSLATOR_EMAIL');

                mkdir("po/$locale");
                mkdir("po/$locale/LC_MESSAGES");
                system("xgettext $files --package-name=$app_name --msgid-bugs-address=$translator_email --package-version=$app_version --language=PHP -o po/$app_name.pot -c");
                system("msginit --locale=$locale -i po/$app_name.pot -o po/$locale/LC_MESSAGES/$domain.po");
            }

        }
    }
}