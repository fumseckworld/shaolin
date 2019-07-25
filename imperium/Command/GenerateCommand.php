<?php


namespace Imperium\Command {


    use Exception;
    use Imperium\Connexion\Connect;
    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class GenerateCommand extends Command
    {
        protected static $defaultName = 'make:command';

        protected function configure()
        {
            $this->setDescription("Generate a new command")->addArgument('name', InputArgument::REQUIRED, 'The command name.');;
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {

            $command  = $input->getArgument('name');

            $dir = COMMAND;


            $namespace = 'Shaolin\\' . $dir;


            $file =   $dir  .DIRECTORY_SEPARATOR. $command .'.php';
            if (!File::exist($file))
            {
                File::create($file);

                File::put($file,"<?php\n\nnamespace $namespace { \n\n\tuse Symfony\Component\Console\Command\Command;\n\tuse Symfony\Component\Console\Input\InputInterface;\n\tuse Symfony\Component\Console\Output\OutputInterface;\n\n\tClass $command extends Command\n\t{\n\n\t\tprotected static \$defaultName = '';\n\n\t\tprotected function configure()\n\t\t{\n\n\t\t}\n\n\t\tpublic function interact(InputInterface \$input, OutputInterface \$output)\n\t\t{\n\n\t\t}\n\n\t\tpublic function execute(InputInterface \$input, OutputInterface \$output)\n\t\t{\n\n\t\t}\n\n\t}\n\n}\n");

                if (File::exist($file))
                    $output->write("<bg=green;fg=white>The $command command was generated successfully\n");

                return 0;
            }
            $output->write("<bg=red;fg=white>The $command command already exist\n");
            return 1;
        }

    }
}