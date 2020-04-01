<?php
    
namespace Eywa\Console\Database
{

    use Eywa\Database\Table\Table;
    use Eywa\Exception\Kedavra;
    use PDO;
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Output\OutputInterface;

    class ShowTable extends Command
    {
        protected static $defaultName = 'table:list';

        protected function configure(): void
        {
            $this->setDescription("List all tables found in the base")
            ->addArgument('env', InputArgument::REQUIRED, 'The base environment');
        }

        /**
         * @param InputInterface $input
         * @param OutputInterface $output
         * @return int
         * @throws Kedavra
         */
        public function execute(InputInterface $input, OutputInterface $output)
        {
            $table = new \Symfony\Component\Console\Helper\Table($output);

            $env = strval($input->getArgument('env'));

            not_in(['dev','prod'], $env, true, 'The env must be dev or prod');


            if (equal($env, 'dev')) {
                switch (development()->driver()) {
                    case MYSQL:
                        $table->setStyle('box')->setHeaders(['Table','Records','Collation','Creation','Update']);
                        $table->setRows(development()
                            ->set(
                                'SELECT TABLE_NAME, TABLE_ROWS,TABLE_COLLATION
                                   CREATE_TIME,UPDATE_TIME  FROM information_schema.tables 
                                   WHERE Table_schema=DATABASE();'
                            )
                            ->get(PDO::FETCH_ASSOC));
                        $table->render();
                        break;
                    case POSTGRESQL:
                        $table->setStyle('box')->setHeaders(['Schema','Table','Records']);
                        $table->setRows(development()
                            ->set('SELECT schemaname,relname,n_tup_ins FROM pg_stat_user_tables;')
                            ->get(PDO::FETCH_ASSOC));
                        $table->render();
                        break;
                    default:
                        $tables = (new Table(development(), ''))->show()->all();
                        break;
                }
            }

            if (equal($env, 'prod')) {
                switch (production()->driver()) {
                    case MYSQL:
                        $table->setStyle('box')->setHeaders(['Table','Records','Collation','Creation','Update']);
                        $table->setRows(production()
                            ->set('SELECT TABLE_NAME ,TABLE_ROWS,TABLE_COLLATION ,CREATE_TIME,UPDATE_TIME 
                                        FROM information_schema.tables WHERE Table_schema=DATABASE();')
                            ->get(PDO::FETCH_ASSOC));
                        $table->render();
                        break;
                    case POSTGRESQL:
                        $table->setStyle('box')->setHeaders(['Schema','Table','Records']);
                        $table->setRows(production()
                                ->set('SELECT schemaname,relname,n_tup_ins FROM pg_stat_user_tables;')
                            ->get(PDO::FETCH_ASSOC));
                        $table->render();
                        break;
                    default:
                        $tables = (new Table(production(), ''))->show()->all();
                        break;
                }
            }

            return 0;
        }
    }
}
