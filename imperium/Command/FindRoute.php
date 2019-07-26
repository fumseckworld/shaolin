<?php


	namespace Imperium\Command
	{


		use Exception;
		use Imperium\Routing\Route;
		use Symfony\Component\Console\Command\Command;
		use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
		use Symfony\Component\Console\Question\Question;

		class FindRoute extends Command
		{
			use Route;

			protected static $defaultName = 'routes:search';

			/**
			 * @var string
			 */
			private $search;

			private function clean()
			{
				clear_terminal();
			}

			protected function configure()
			{
				$this->setDescription('Find a route');
			}


			private function controllers(): array
			{
				return controllers();

			}

			private function methods(): array
			{
				return collect(METHOD_SUPPORTED)->each('strtolower')->all();
			}

			private function names(): array
			{
				$data = collect();
				foreach ($this->routes()->query()->mode(SELECT)->from('routes')->only('name')->get() as $x)
					$data->push($x->name);

				return $data->all();
			}

			public function data(): array
			{
				return collect()->merge($this->controllers(), $this->names(), $this->methods())->all();
			}

			/**
			 * @param InputInterface  $input
			 * @param OutputInterface $output
			 *
			 * @throws Exception
			 * @return int|void
			 */
			public function interact(InputInterface $input, OutputInterface $output)
			{
				if (def($this->routes()->all()))
				{
					$helper = $this->getHelper('question');

					do
					{

						do
						{
							$this->clean();
							$question = new Question("<info>Please enter the search value : </info>");
							$question->setAutocompleterValues($this->data());
							$this->search = $helper->ask($input, $output, $question);
						} while (is_null($this->search));

						$routes = $this->routes()->search($this->search);

						routes($output, $routes);
						$question = new Question("<info>Continue searching ? [Y/n] : </info>", 'Y');
						$continue = strtoupper($helper->ask($input, $output, $question));
						$continue = $continue === 'Y';

					} while ($continue);

				}

			}

			public function execute(InputInterface $input, OutputInterface $output)
			{
				$this->clean();
				if (def($this->routes()->all()))
				{
					$output->write("<info>Bye</info>\n");
				} else
				{
					$output->write("<error>We have not found routes</error>\n");
				}
			}

		}
	}