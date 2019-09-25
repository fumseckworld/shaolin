<?php
	
	namespace Imperium\Command;
	
	use Imperium\Encrypt\Crypt;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
	
	class KeyGenerate extends Command
	{
		protected static $defaultName = "key:generate";
		
		protected function configure()
		{
			$this->setDescription('Generate the application key');
		}

		public function execute(InputInterface $input, OutputInterface $output)
		{
            $key = Crypt::generateKey();
            if((new File('.env',EMPTY_AND_WRITE_FILE_MODE))->write("APP_KEY=$key")->flush())
            {
                $output->writeln('<info>The app key was generated successfully</info>');
                return 0;
            }
            $output->writeln('The app key has not been generated');
			return 1;
		}
		
	}