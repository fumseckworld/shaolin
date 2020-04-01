<?php
    
namespace Eywa\Console\App
{

    use Eywa\Console\Shell;
    use Eywa\Http\Server\Http;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Coverage extends Command
    {
        protected static $defaultName = "app:coverage";
            
            
        protected function configure(): void
        {
            $this->setDescription('Run a server to show the code coverage');
        }
        
            
        /**
         * @param  InputInterface   $input
         * @param  OutputInterface  $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input, $output);

            return (new Http($io, 'coverage', 8000))->run();
        }
    }
}
