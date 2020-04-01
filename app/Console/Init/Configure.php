<?php

namespace App\Console\Init;

use Eywa\File\File;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Configure extends Command
{
    protected static $defaultName = 'app:configure';

    protected function configure(): void
    {
        $this->setDescription('generate startup file');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $x =  collect();
        $io = new SymfonyStyle($input, $output);

        $x->push(copy('.env.example', '.env'));
        $x->push(
            (new File(base('config', 'mode.yaml'), EMPTY_AND_WRITE_FILE_MODE))
            ->write("mode: up\nconnexion: dev")->flush()
        );




        if ($x->ok()) {
            $io->success('the app is ready to use');
            return 0;
        }

        $io->error('Configguration problem has been found');

        return 1;
    }
}
