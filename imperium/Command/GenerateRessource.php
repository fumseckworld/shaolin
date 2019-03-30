<?php


namespace Imperium\Command {


    use Imperium\File\File;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class GenerateRessource extends Command
    {
        protected static $defaultName = 'make:resource';

        protected function configure()
        {
            $this
                // the short description shown while running "php bin/console list"
                ->setDescription('Create a new resource')
                ->setAliases(['r'])
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp('php shaolin make:resource name')
                ->addArgument('resource', InputArgument::REQUIRED, 'The resource name.');
        }

        public function execute(InputInterface $input, OutputInterface $output)
        {
            $controller = ucfirst(str_replace('Controller','',$input->getArgument('resource')));

            append($controller,'Controller');
            $controllers = collection(config('app','dir'))->get('controller');

            $core  = core_path(collection(config('app','dir'))->get('app'));

            $namespace = config('app','namespace') . '\\' . $controllers;


            $file =  $core .DIRECTORY_SEPARATOR . $controllers  .DIRECTORY_SEPARATOR. $controller .'.php';

            if (File::not_exist($file))
            {
                File::create($file);

                File::put($file,"<?php\n\nnamespace $namespace { \n\n\tuse Imperium\Controller\Controller;\n\tuse Imperium\Model\Model;\n\tuse Symfony\Component\HttpFoundation\RedirectResponse;\n\n\tClass $controller extends Controller\n\t{\n\n\t\tconst TABLE = '';\n\n\t\t/**\n\t\t*\n\t\t* @var Model\n\t\t*\n\t\t**/\n\t\tprivate \$model;\n\n\t\tpublic function before_action()\n\t\t{\n\t\t\t\$this->model =  \$this->model()->from(self::TABLE);\n\t\t}\n\n\t\tpublic function create(): string\n\t\t{\n\t\t\t return \$this->model->create_form(self::TABLE,route('',POST),id(),'create',fa('fas','fa-plus'));\n\t\t}\n\n\t\tpublic function edit(int \$id): string\n\t\t{\n\t\t\treturn \$this->model->edit_form(self::TABLE,\$id,route('',POST),id(),'update',fa('fas','fa-sync'));\n\t\t}\n\n\t\tpublic function update(): RedirectResponse\n\t\t{\n\t\t\t return \$this->model->update() ? back('success') : back('fail',false);\n\t\t}\n\n\t\tpublic function destroy(int \$id): RedirectResponse\n\t\t{\n\t\t\treturn \$this->model->remove(\$id) ? back('success') : back('fail',false);\n\t\t}\n\n\t}\n}\n");

                if (File::exist($file))
                    $output->write("<bg=green;fg=white>The $controller controller was generated successfully\n");

                return 0;
            }

            $output->write("<bg=red;fg=white>The $controller controller already exist\n");

            return 1;
        }

    }
}