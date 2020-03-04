<?php


namespace Eywa\Console\Lang {


    use Exception;
    use Eywa\Console\Shell;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UpdateCatalogues extends Command
    {
        protected static $defaultName = "catalogues:refresh";

        protected function configure():void
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
                    $io->error(sprintf('The %s catologue has been updated successfully',$catalogue));
                    $x->push(true);
                }else{
                    $io->error(sprintf('The %s catologue update has failed',$catalogue));
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

        /**
         * @param string $locale
         * @return bool
         *
         *
         * @throws Exception
         */
        public function update(string $locale): bool
        {
            if (is_dir("po/$locale"))
            {
                $view_base = files(base('app','Views','*.php'));
                $view_prof = files(base('app','Views','*','*.php'));

                $x = array_merge($view_base,$view_prof);

                $files = collect($x)->join(' ');

                $app_name = strval(env('APP_NAME','eywa'));
                $po = base('po',$locale,'LC_MESSAGES','messages.po');
                $pot = base('po',"$app_name.pot");
                $app_version = strval(env('APP_VERSION','1.0'));
                $translator_email = strval(env('TRANSLATOR_EMAIL','translator@free.fr'));

                if((new Shell("xgettext --keyword=_ --language=PHP --add-comments  --msgid-bugs-address=$translator_email --package-version=$app_version  --package-name=$app_name  --sort-output -o $pot $files"))->run() &&    (new Shell("msgmerge --update $po $pot"))->run())
                    return true;

                return false;

            }
            return false;

        }
    }
}