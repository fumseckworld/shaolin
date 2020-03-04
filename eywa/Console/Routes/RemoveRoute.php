<?php

namespace Eywa\Console\Routes {


    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class RemoveRoute extends Command
    {
        protected static $defaultName = "route:destroy";


        /**
         *
         * The route name
         *
         */
        private string $route = '';


        protected function configure():void
        {
            $this->setDescription('Delete a route');
        }

        /**
         * @return array<string>
         * @throws Kedavra
         */
        public function name():array
        {
            $x = collect();

                foreach (Web::all() as $v)
                    $x->push($v->name);
                return $x->all();
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output):int
        {
            $io = new SymfonyStyle($input,$output);

            if (def(Web::all()))
            {
                do
                {
                    do
                    {
                        $names = $this->name();
                        $default = strval(reset($names));
                        $this->route = $io->askQuestion((new Question('Wath is the name of the route to delete',$default))->setAutocompleterValues($this->name()));
                    } while (not_def($this->route) || not_def(Web::by('name',$this->route)));


                    $route = intval(Web::by('name',$this->route)[0]->id);

                    $name = strval(Web::by('name',$this->route)[0]->name);


                    if (Web::destroy($route))
                        $io->success(sprintf('The %s route has been deleted successfully',$name));
                    else
                        $io->error(sprintf('The %s route has not been deleted',$name));

                }while($io->confirm('Continue ?',true));
                $io->success('Bye');
                return 0;
            }

            $io->warning('No routes has been found');

            return  0;
        }

    }
}