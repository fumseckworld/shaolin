<?php


namespace Eywa\Console\Lang {


    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class UpdateCatalogues extends Command
    {
        protected static $defaultName = "catalogues:refresh";

        protected function configure()
        {

            $this->setDescription('Update all catalogues');

        }
        public function execute(InputInterface $input, OutputInterface $output)
        {
            collect(config('i18n','locales'))->for([$this,'update'])->ok();
            return 0;

        }

        public function update($locale)
        {
            if (is_dir("po/$locale"))
            {
                $files = collect(glob(base('app','Views','*.php')))->merge(glob(base('app','Views','*','*.php')))->join(' ');

                $app_name = env('APP_NAME','eywa');
                $po = base('po',$locale,'LC_MESSAGES','messages.po');
                $pot = base('po',"$app_name.pot");
                $app_version = env('APP_VERSION','1.0');
                $translator_email = env('TRANSLATOR_EMAIL','translator@free.fr');

                system("xgettext --keyword=_ --language=PHP --add-comments  --msgid-bugs-address=$translator_email --package-version=$app_version  --package-name=$app_name  --sort-output -o $pot $files");
                system("msgmerge --update $po $pot");


            }

        }
    }
}