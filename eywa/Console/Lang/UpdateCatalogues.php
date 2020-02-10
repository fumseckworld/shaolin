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
                $files = collect(glob(base('app','views') .DIRECTORY_SEPARATOR.'*.php'))->merge(glob(base('app','views').DIRECTORY_SEPARATOR. '*'.DIRECTORY_SEPARATOR.'*.php'))->join(' ');
                $app_name = env('APP_NAME');
                $po = base('po',$locale,'LC_MESSAGES') .DIRECTORY_SEPARATOR . 'messages.po';
                $pot = base('po') . DIRECTORY_SEPARATOR . $app_name. '.pot';
                system("xgettext --language=PHP --add-comments --sort-output -o $pot  --sort-output  $po $pot $files");
                system("msgmerge --update $po $pot");


            }

        }
    }
}