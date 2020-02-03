<?php


namespace Eywa\Console\App;


use Eywa\File\File;
use Eywa\Security\Crypt\Crypter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Key extends Command
{

    protected static $defaultName = 'key:generate';

    protected function configure()
    {

        $this->setDescription('Generate app key');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $f = fopen('.env','r');
        if (!$f)
        {
            $output->writeln("Failed to open the .env file");
            return 1;
        }
        $app_key = Crypter::generateKey();

        $lines = collect();
        while(!feof($f))
        {
            $lines->push(fgets($f));

        }

        is_false(fclose($f),true,"Fail to close file");

        $f = fopen('.env','w+');
        if (!$f)
        {
            $output->writeln("Failed to open the .env file");
            return 1;
        }

        foreach ($lines->all() as $line)
        {
            $key = collect(explode('=',$line))->first();
            if (equal($key,'APP_KEY'))
                fputs($f,"APP_KEY='$app_key'\n");
            else
                fputs($f,$line);
        }
        return fclose($f) ? 0 : 1;
    }
}