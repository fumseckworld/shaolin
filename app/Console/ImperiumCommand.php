<?php

namespace App\Console;



use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ImperiumCommand  extends Command
{
    protected static $defaultName = 'tests';
    
    public function execute(InputInterface $input, OutputInterface $output)
	{
		return system('./vendor/bin/phpunit');
	}
	
}