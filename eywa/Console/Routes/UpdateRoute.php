<?php

namespace Eywa\Console\Routes {

    use Eywa\Collection\Collect;
    use Eywa\Exception\Kedavra;
    use Eywa\Http\Routing\Web;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;
    use Symfony\Component\Console\Question\Question;
    use Symfony\Component\Console\Style\SymfonyStyle;

    class UpdateRoute extends Command
    {

        protected static $defaultName = "route:update";

        /**
         *
         * The result asked
         *
         */
        private Collect $entry;

        /**
         *
         * The value to find
         *
         */
        private string $search = '';

        protected function configure():void
        {
            $this->setDescription('Update a route');
        }

        /**
         * @return array<string>
         * @throws Kedavra
         */
        private function name()
        {
            $x = collect();

                foreach (Web::all() as $v)
                    $x->push($v->name);
                return $x->all();
        }

        /**
         *
         * @param InputInterface $input
         * @param OutputInterface $output
         *
         *
         * @return int
         * @throws Kedavra
         */
        public function interact(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);
            $this->entry = collect();

            if (def(Web::all()))
            {
                do
                {

                    do{
                        $this->search = $io->askQuestion((new Question('What is the name of the route to update ?','root'))->setAutocompleterValues($this->name()));
                    }while(not_def(Web::by('name',$this->search)));


                    $route = Web::by('name', $this->search)[0];


                    do
                    {
                        $this->entry->put('name', $io->askQuestion((new Question('Change the route name ? ',$route->name))));
                    }while(not_def($this->entry->get('name')) || def(Web::by('name',$this->entry->get('name'))) && $this->entry->get('name') !== $route->name);

                    do
                    {
                        $this->entry->put('method', strtoupper($io->askQuestion((new Question('Change the route method ? ',$route->method)))));
                    }while(not_def($this->entry->get('method')) || not_in(METHOD_SUPPORTED,$this->entry->get('method')));

                    do
                    {
                        $this->entry->put('url', $io->askQuestion((new Question('Change the route url ? ',$route->url))));
                    }while(not_def($this->entry->get('url')) || def(Web::by('url',$this->entry->get('url'))) && $this->entry->get('url') !== $route->url);

                    do
                    {
                        $this->entry->put('controller', $io->askQuestion((new Question('Change the route controller ? ',$route->controller))));
                    }while(not_def($this->entry->get('controller')));
                    do
                    {
                        $this->entry->put('action', $io->askQuestion((new Question('Change the route action ? ',$route->action))));
                    }while(not_def($this->entry->get('action')) || def(Web::by('action',$this->entry->get('action'))) && $this->entry->get('action') !== $route->action);
                    do
                    {
                        $this->entry->put('directory', $io->askQuestion((new Question('Change the route namespace ? ',$route->directory))));
                    }while(not_def($this->entry->get('directory')));

                    $this->entry->put('created_at',$route->created_at);
                    $this->entry->put('updated_at',now()->toDateTimeString());


                    if(Web::update(intval($route->id),$this->entry->all()))
                    {
                        $io->success('The route has been updated successfully');
                    }else{
                        $io->error('The route has not been updated');
                        return 1;
                    }

                    $this->entry->clear();

                }while($io->confirm('Continue to update routes ?',true));
                return 0;
            }
            $io->warning('Cannot update a route no routes has been found');
            return 0;

        }


        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $io = new SymfonyStyle($input,$output);
            $io->success('Bye');
            return 0;
        }
    }
}