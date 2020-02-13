<?php


namespace Eywa\Console\Lang {


    use Eywa\Console\Shell;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UpdateCatalogues extends Command
    {
        protected static $defaultName = "catalogues:refresh";

        protected function configure()
        {

            $this->setDescription('Update all catalogues');

        }
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $x = collect();
            $io = new SymfonyStyle($input,$output);

            $io->title('Create localization catalog');
            foreach (config('i18n','locales') as $catalogue)
            {
                if ($this->update($catalogue))
                {
                    $io->success("The $catalogue catalog has been updating successfully");
                    $x->push(true);
                }else{
                    $io->error("The update $catalogue catalog has been fail");
                    $x->push(false);
                }
            }

            if ($x->ok())
            {

                $io->success('All catalogs has been updated successfully');
                return 0;
            }
            $io->error('Errors has been encontred');
            return 1;


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

                if((new Shell("xgettext --keyword=_ --language=PHP --add-comments  --msgid-bugs-address=$translator_email --package-version=$app_version  --package-name=$app_name  --sort-output -o $pot $files"))->run() &&    (new Shell("msgmerge --update $po $pot"))->run())
                    return true;

                return false;


            }

        }
    }
}