<?php

namespace Eywa\Console\Routes {


    use Exception;
    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
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
         */
        private Sql $sql;

        /**
         * FindRoute constructor.
         * @param string|null $name
         *
         * @throws Kedavra
         *
         */
        public function __construct(string $name = null)
        {
            parent::__construct($name);

            $this->sql =  (new Sql(connect(SQLITE,base('routes','web.sqlite3')),'routes'));
        }

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
            $names= collect();

            foreach ((new Sql(connect(SQLITE,base('routes','web.sqlite3')),'routes'))->only(['name'])->get() as $value)
                $names->push($value->name);

            return $names->all();
        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         * @throws Exception
         */
        public function execute(InputInterface $input, OutputInterface $output):int
        {
            $io = new SymfonyStyle($input,$output);
            if (def($this->sql->get()))
            {
                do
                {

                    $this->sql =  (new Sql(connect(SQLITE,base('routes','web.sqlite3')),'routes'));
                    if (def($this->sql->get()))
                    {
                        do
                        {
                            $names = $this->name();
                            $default = strval(reset($names));
                            $this->route = $io->askQuestion((new Question('Wath is the name of the route to delete',$default))->setAutocompleterValues($this->name()));
                        }while (not_def($this->route) || not_def($this->sql->where('name',EQUAL,$this->route)->get()));

                        $route = collect($this->sql->where('name',EQUAL,$this->route)->get())->get(0);

                        if ($this->sql->where('name',EQUAL,$route->name)->delete())
                            $io->success(sprintf('The %s route has been deleted successfully',$route->name));
                        else
                            $io->error(sprintf('The %s route has not been deleted',$route->name));

                    }else{
                        $io->warning('All routes has been deleted');

                        return  0;
                    }
                }while($io->confirm('Continue ?',true));
            }


            $io->warning('No routes has been found');

            return  0;
        }

    }
}