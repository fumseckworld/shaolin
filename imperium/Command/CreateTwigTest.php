<?php

        namespace Imperium\Command;

		use Imperium\Exception\Kedavra;
        use Imperium\File\File;
        use Symfony\Component\Console\Command\Command;
        use Symfony\Component\Console\Input\InputArgument;
        use Symfony\Component\Console\Input\InputInterface;
		use Symfony\Component\Console\Output\OutputInterface;
        use Twig\TwigTest;

        class CreateTwigTest extends Command
		{

			protected static $defaultName = 'twig:test';

			protected function configure()
			{
				$this->setDescription('Add twig test extension')->addArgument('name',InputArgument::REQUIRED,'The class name');
			}

            /**
             * @param InputInterface $input
             * @param OutputInterface $output
             * @return int|null
             * @throws Kedavra
             */
			public function execute(InputInterface $input, OutputInterface $output)
			{
                $class = ucfirst($input->getArgument('name'));
                $dir = base('app') .DIRECTORY_SEPARATOR . 'Twig'.DIRECTORY_SEPARATOR . 'Tests'. DIRECTORY_SEPARATOR. $class;
                $file = $dir .'.php';
                if (file_exists($file))
                {
                    $output->writeln('<bg=red;fg=white>file already exist</>');
                    return 1;
                }

                if ((new File($file,EMPTY_AND_WRITE_FILE_MODE))->write("<?php\n\n\tnamespace App\Twig\Tests;\n\tuse Twig\Extension\AbstractExtension;\n\tuse Twig\TwigTest;\n\n\tclass {$class} extends AbstractExtension\n\t{\n\t\n\t\tpublic function getTests()\n\t\t{\n\t\t\treturn [\n\t\t\t\tnew TwigTest('', '')\n\t\t\t];\n\n\t\t}\n\t}")->flush())
                {
                    $output->writeln("<info>function was generated successfully</info>");
                    return 0;
                }
                return 1;

			}

		}
