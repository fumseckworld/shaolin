<?php


namespace Eywa\Console\Lang {

;
    use Exception;
    use Eywa\Console\Shell;
    use Eywa\Exception\Kedavra;
    use Gettext\Generator\PoGenerator;
    use Gettext\Loader\PoLoader;
    use Gettext\Scanner\PhpScanner;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class CreateCatalogues extends Command
    {
        protected static $defaultName = "catalogues:generate";


        protected function configure():void
        {
            $this->setDescription('Create all catalogues');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->title('Create localization catalog');
            $x = collect();

            foreach (config('i18n', 'locales') as $locale) {
                if ($this->generate($locale)) {
                    $io->title(sprintf('Generating the %s cataloge', $locale));
                    $io->success(sprintf('The %s catalog has been generated successfully', $locale));
                    $x->push(true);
                } else {
                    $io->warning(sprintf('The %s catalog already exist', $locale));
                    $x->push(false);
                }
            }
            if ($x->ok()) {
                $io->success("All catalogs has been successfully generated");
                return 0;
            }

            $io->error('Catalogs already exist');
            return  1;
        }

        /**
         * @param string $locale
         * @return bool
         * @throws Exception
         *
         */
        public function generate(string $locale): bool
        {
            if (!is_dir('po')) {
                mkdir('po');
            }
            if (!is_dir("po/$locale")) {
                $view_base = files(base('app', 'Views', '*.php'));
                $view_prof = files(base('app', 'Views', '*', '*.php'));

                $x = array_merge($view_base, $view_prof);

                $files = collect($x)->join(' ');

                $app_name = strval(env('APP_NAME', 'eywa'));

                $app_version = strval(env('APP_VERSION', '1.0'));

                $translator_email = strval(env('TRANSLATOR_EMAIL', 'translator@free.fr'));


                return true;
            }
            return false;
        }
    }
}
