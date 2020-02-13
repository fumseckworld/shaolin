<?php
	
	namespace Eywa\Console\Generate;
	

    use Symfony\Component\Console\Command\Command;
	use Symfony\Component\Console\Input\InputArgument;
	use Symfony\Component\Console\Input\InputInterface;
	use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class GenerateView extends Command
	{
		
		protected static $defaultName = "make:view";
		
		protected function configure()
		{
			
			$this->setDescription('Generate a view')->addArgument('view', InputArgument::REQUIRED, 'the view name')->addArgument('dir', InputArgument::OPTIONAL, 'the view dir');
		}
		
		/**
		 * @param  InputInterface   $input
		 * @param  OutputInterface  $output
		 *
         * @return int|void|null
		 */
		public function execute(InputInterface $input, OutputInterface $output)
		{
            $io = new SymfonyStyle($input,$output);

            $io->title('Generation of the view');
			$dir = $input->getArgument('dir') ?? '';

			if (def($dir))
            {

                if (!is_dir(base('app','Views',$dir)))
                    mkdir(base('app','Views',$dir));

            }
			
			$view =  collect(explode('.', $input->getArgument('view')))->first();
			
			append($view, '.php');
			$view = def($dir) ? base('app','Views',$dir,$view) : base('app','Views',$view);

			if (file_exists($view))
            {
                $io->error('The view already exist');
                return 1;
            }

			if (touch($view))
			{
                $io->success('The view was generated successfully');
                return 0;

            }
            $io->error('Failed to generate the view');
			return 1;


        }
		
	}