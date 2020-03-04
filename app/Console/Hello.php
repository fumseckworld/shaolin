<?php


namespace App\Console;


use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Hello extends Command
{
    protected static $defaultName = "hello";

    protected function configure()
    {

        $this->setDescription('Say hello');

    }
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input,$output);

        $io->success('Welcome at shaolin');

        return 0;
    }

}