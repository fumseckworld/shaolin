<?php
	
	namespace Imperium\Command
	{
		
		use Imperium\Exception\Kedavra;
        use Imperium\Model\Admin;
        use Imperium\Model\Web;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Symfony\Component\Console\Question\Question;

        class ListRoute extends \Symfony\Component\Console\Command\Command
		{
			
			protected static $defaultName = "route:list";
            /**
             * @var string
             */
            private $choose;

            protected function configure()
			{
				
				$this->setDescription('List all routes');
			}

			public function interact(InputInterface $input, OutputInterface $output)
            {
                $helper = $this->getHelper('question');
                do {
                    clear_terminal();

                    $question = new Question("<info>Route for admin or web ?</info> : ");

                    $question->setAutocompleterValues(['admin', 'web']);

                    $this->choose = $helper->ask($input, $output, $question);

                } while (is_null($this->choose) || not_in(['admin', 'web'], $this->choose));

            }

            /**
			 *
			 * List routes
			 *
			 * @param  InputInterface   $input
			 * @param  OutputInterface  $output
			 *
			 * @throws Kedavra
			 * @return int|null
			 *
			 */
			public function execute(InputInterface $input, OutputInterface $output)
			{

                $this->choose == 'admin' ? routes($output,Admin::all()) :  routes($output, Web::all());
				
				return 0;
			}
			
		}
	}