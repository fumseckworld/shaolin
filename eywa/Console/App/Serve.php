<?php
    
namespace Eywa\Console\App
{

    use Eywa\Http\Server\Http;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class Serve extends Command
    {
        protected static $defaultName = "app:run";
            
            
        protected function configure(): void
        {
            $this->setDescription('Run a development server');
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

            return (new Http($io))->run();
        }
    }
}
