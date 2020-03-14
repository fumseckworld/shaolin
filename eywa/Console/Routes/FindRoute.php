<?php

namespace Eywa\Console\Routes {


    use Eywa\Database\Query\Sql;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Helper\Table;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class FindRoute extends Command
    {

        protected static $defaultName = "route:find";

        /**
         *
         * The search value
         *
         */
        private string $search ='';
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

        protected function configure():void
        {
            $this->setDescription('Find a route');
        }


        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output):int
        {

            $io  = new SymfonyStyle($input,$output);
            do
            {
                do
                {
                    $this->search = $io->askQuestion((new Question('Type your search '))->setAutocompleterValues($this->all()));
                } while (not_def($this->search));

                $table = new Table($output);
                $io = new SymfonyStyle($input,$output);


                $table
                    ->setStyle('box')

                    ->setHeaders(['id', 'method', 'name','url','controller','action','namespace','created','updated'])
                    ->setRows(
                        $this->sql->like($this->search)->to(PDO::FETCH_ASSOC)

                    )
                ;
                $table->render();
            }while($io->confirm('Continue ?',true));
            return 0;
        }





        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         * @return int|null
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {

            $io  = new SymfonyStyle($input,$output);

            $io->success('Bye');
            return 0;
        }


        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function controller(): array
        {
            $x = collect();

            foreach ($this->sql->get() as $v)
                $x->push($v->controller);
            return $x->all();


        }


        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function name(): array
        {
            $x = collect();

            foreach ($this->sql->get() as $v)
                $x->push($v->name);
            return $x->all();

        }


        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function url(): array
        {
            $x = collect();

            foreach ($this->sql->get() as $v)
                $x->push($v->url);
            return $x->all();


        }


        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function action():array
        {
            $x = collect();

                foreach ($this->sql->get() as $v)
                    $x->push($v->action);
                return $x->all();

        }


        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function all():array
        {
            return collect()->merge( collect(METHOD_SUPPORTED)->for('strtolower')->all())->merge($this->name())->merge($this->url())->merge($this->action())->merge($this->controller())->all();
        }

    }
}