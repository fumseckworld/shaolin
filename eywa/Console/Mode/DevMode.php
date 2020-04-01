<?php

namespace Eywa\Console\Mode {


    use Eywa\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class DevMode extends Command
    {
        protected static $defaultName = 'app:dev';

        protected function configure(): void
        {
            $this->setDescription('Put the application in development mode');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            $io->title('Enabling the development mode');
            if ((new File('config/mode.yaml', EMPTY_AND_WRITE_FILE_MODE))->write("mode: up\nconnexion: dev")->flush()) {
                $io->success('The application is now in development mode');
                return 0;
            }
            $io->error('The change mode command has fail');
            return 1;
        }
    }
}
