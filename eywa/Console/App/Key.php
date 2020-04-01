<?php

namespace Eywa\Console\App;

use Eywa\Security\Crypt\Crypter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Key extends Command
{
    protected static $defaultName = 'key:generate';

    protected function configure(): void
    {
        $this->setDescription('Generate app key');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title('Generation of the app key');

        if (!file_exists('.env')) {
            $io->error('The .env file not exist');
            return 1;
        }
        $f = fopen('.env', 'r');
        if (!$f) {
            $io->error("Failed to open the .env file");
            return 1;
        }
        $app_key = Crypter::generateKey();

        $lines = collect();
        while (!feof($f)) {
            $lines->push(fgets($f));
        }

        is_false(fclose($f), true, "Fail to close file");

        $f = fopen('.env', 'w+');
        if (!$f) {
            $io->error("Failed to open the .env file");
            return 1;
        }

        foreach ($lines->all() as $line) {
            $key = collect(explode('=', $line))->first();
            if (equal($key, 'APP_KEY')) {
                fputs($f, "APP_KEY='$app_key'\n");
            } else {
                fputs($f, $line);
            }
        }
        $io->success("The app key has been defined successfully");
        return fclose($f) ? 0 : 1;
    }
}
