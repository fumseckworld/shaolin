<?php
	
	namespace Imperium\Command
	{

        use Imperium\Action\Todo;
        use Imperium\Directory\Dir;
		use Imperium\Exception\Kedavra;
        use Imperium\Model\Task;
        use Imperium\Model\Web;
		use Imperium\Model\Admin;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		
		class GenerateRouteBase extends Command
		{
			
			protected static $defaultName = 'route:generate';
			
			protected function configure()
			{
				
				$this->setDescription('Generate all routes bases');
			}
			
			/**
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 * @return bool|int|null
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{
	            Web::generate();
	            Admin::generate();
	            Task::generate();
	            Todo::generate();
	            return 0;

			}
			
		}
	}
