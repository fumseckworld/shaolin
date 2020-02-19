<?php

namespace Eywa\Console\Mode {


    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class ProductionMode extends Command
    {

        protected static $defaultName = 'app:prod';

        protected function configure()
        {

            $this->setDescription('Put the application in production mode');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            $io = new SymfonyStyle($input,$output);

            $io->title('Enabling the production mode');

            if ((new File('config/mode.yaml',EMPTY_AND_WRITE_FILE_MODE))->write("mode: up\nconnexion: prod")->flush())
            {
                $io->success('The application is now in production mode');
                return 0;
            }
            $io->error('The change mode command has fail');
            return 1;
        }

    }
}
